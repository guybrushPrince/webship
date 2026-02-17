<?php

namespace webship\protocols;

use CliffSerializable;
use DatabaseError;
use Event;
use Exception;
use ship\interoperability\IShippable;
use ship\interoperability\ShipperRegister;
use ship\protocols\ModifyProtocol;
use ship\protocols\Protocol;
use ship\protocols\TemplateProtocol;
use ship\template\TemplateObject;
use ship\views\DirectView;
use ship\views\View;
use webship\core\HtmlView;
use webship\interoperability\ParameterSerializer;
use webship\JSProperty;
use webship\WebshipTools;

/**
 * Class HtmlModifyProtocol.
 *
 * <p>{@link HtmlModifyProtocol} is an implementation of the {@link ModifyProtocol} that generates HTML, JS, and CSS
 * code for dynamic websites.</p>
 *
 * @package webship
 * @subpackge php\controller
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class HtmlModifyProtocol extends ModifyProtocol {

    /**
     * Constructor.
     * @param Protocol|DirectView|null $parent The parent protocol or view.
     */
    public function __construct(Protocol|DirectView|null $parent) {
        parent::__construct(
            $parent,
            HtmlActionProtocol::class,
            HtmlDialogProtocol::class
        );
    }

    /**
     * Create a new html modify protocol for a given {@link HtmlView}.
     * @param HtmlView $view The view binding.
     * @return HtmlModifyProtocol
     */
    public static function _new(HtmlView $view) : HtmlModifyProtocol {
        return new HtmlModifyProtocol($view);
    }

    /**
     * Create a new html modify protocol for a given {@link HtmlView}.
     * @param HtmlView $view The view binding.
     * @return HtmlModifyProtocol
     */
    public static function create(HtmlView $view) : HtmlModifyProtocol {
        return new HtmlModifyProtocol($view);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function generate() : string {
        return implode(array_map(function (array $modify) {
            switch ($modify["operation"]) {
                case 'alert':
                case 'action': {
                    return $modify["protocol"]->generate();
                }
                case 'doIt': {
                    if ($modify['protocol'] === null) {
                        $modify['protocol'] = TemplateProtocol::get($modify['name'], $modify['environment']);
                    }
                    return $modify["protocol"]->generate($modify['context'], $this->innerView());
                }
                case 'virtualDoIt': {
                    // Idea: The appended protocol is wrapped in an anonymous function that is called.
                    $anonymousProtocol = $modify["protocol"]; // It is a template object
                    if ($anonymousProtocol instanceof TemplateObject) {
                        return 'callFunc(' . $this->asContext($anonymousProtocol) . ', result);';
                    } else return '';
                }
                case 'setCookie': {
                    return 'setCookie(' . ($modify["value"] !== '' ? 'true' : 'false') . ', ' . ($modify["value"] ? '"' . $modify["value"] . '"' : 'undefined') . ', result);';
                }
                case 'set': {
                    $val = $modify["value"];
                    if ($val === false) {
                        $val = 'result';
                    } else if ($val instanceof View) {
                        $scope = $modify["scope"];
                        $selector = '$("' . $val->idc() . '")';
                        if ($scope === "value") {
                            $selector .= '.val()';
                        } else if (!$scope) {
                            $selector .= '.attr("' . $scope . '")';
                        }
                        $val = $selector;
                    } else if ($val instanceof JSProperty) {
                        $val = $val->getName();
                    } else if ($val instanceof TemplateObject) {
                        $val = str_replace('"', "'", $this->asContext($val));
                    } else if ($val instanceof IShippable) {
                        $val = '"' . ShipperRegister::getObjectString($val) . '"';
                    } else {
                        $val = (is_string($val) ? '"' . $val . '"' : $val);
                    }
                    return 'setContext("' . $modify["key"] . '", ' . $val . ');';
                }
                case 'reload': {
                    return 'location.reload();';
                }
                case 'get': {
                    return 'context.' . $modify["key"] . ';';
                }
                case 'delete': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return 'delElem(' . $this->createViewSelector($view) . ', ' . Event::DELETE . ');';
                    else return '';
                }
                case 'resize': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return 'resize(' . $this->createViewSelector($view) . ');';
                    else return '';
                }
                case 'reheight': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return 'reheight(' . $this->createViewSelector($view) . ');';
                    else return '';
                }
                case 'reset': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return 'reset(' . $this->createViewSelector($view) . ');';
                    else return '';
                }
                case 'focus': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return 'focus(' . $this->createViewSelector($view) . ');';
                    else return '';
                }
                case 'value': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        $value = $modify["value"];
                        $template = '';
                        if ($value instanceof TemplateObject) {
                            $value = $value->getName();
                            $template = ', con';
                        }
                        return 'setValue(' . $this->createViewSelector($view) . ', "' . $value . '"' . $template . ');';
                    } else return '';
                }
                case 'enable':
                case 'disable': {
                    $disabled = ($modify["operation"] === 'disable') ? '' : ', false';
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return 'disEnable(' . $this->createViewSelector($view) . $disabled . ');';
                    else return '';
                }
                case 'scrollTop': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return $this->createViewSelector($view) . '.scrollTop(0);';
                    else return '';
                }
                case 'add':
                case 'addWithFallback':
                case 'prepend':
                case 'replace':
                case 'before':
                case 'after':
                case 'text': {
                    return $this->generateDomAction($modify);
                }
                case 'event':
                case 'throw': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        $data = $this->generateData($modify["data"]);
                        $event = $this->handleEventType($modify["event"]);
                        return 'triggerEvent(' . $this->createViewSelector($view) . ', ' . $event . $data . ');';
                    } else {
                        return '';
                    }
                }
                case 'throwObjectEvent': {
                    $obj = $modify["object"];
                    if ($obj instanceof IShippable || $obj instanceof TemplateObject || (is_array($obj) && !WebshipTools::isEmpty($obj))) {
                        $event = $this->handleEventType($modify["event"]);
                        $scopes = $this->generateScopes($modify["scopes"]);
                        $data = $this->generateData($modify["data"]);
                        $objIds = $this->createObjectIdList($obj);
                        return 'eventBus.trigger([' . implode(',', $objIds) . '], ' .
                            $event . ', _i_n_' . $scopes . $data . ');';
                    } else {
                        return '';
                    }
                }
                case 'unbindObjectEvent': {
                    $obj = $modify["object"];
                    if ($obj instanceof IShippable || $obj instanceof TemplateObject || (is_array($obj) && !WebshipTools::isEmpty($obj))) {
                        $event = $this->handleEventType($modify["event"]);
                        $scopes = $this->generateScopes($modify["scopes"]);
                        $objIds = $this->createObjectIdList($obj);
                        $view = $this->reduceView($modify["view"]);
                        return 'eventBus.unbind(' . $this->createViewSelector($view) . ', ' .
                            '[' . implode(',', $objIds) . '], ' . $event . $scopes . ');';
                    } else {
                        return '';
                    }
                }
                case 'clear': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return 'clear(' . $this->createViewSelector($view) . ');';
                    else return '';
                }
                case 'addClasses': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return $this->createViewSelector($view) .
                            '.addClass(' . $this->generateClasses($modify['classes']) . ');';
                    } else return '';
                }
                case 'removeClasses': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return $this->createViewSelector($view) .
                            '.removeClass(' . $this->generateClasses($modify['classes']) . ');';
                    } else return '';
                }
                case 'empty': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return $this->createViewSelector($view) . '.empty();';
                    else return '';
                }
                case 'removeClassesSiblings': {
                    $view = $this->reduceView($modify["view"]);
                    $onlyChildren = $this->asContext($modify["children"]);
                    if ($view) return 'remClassSib(' . $this->createViewSelector($view) . ',' .
                        $this->generateClasses($modify["classes"], null) . ($onlyChildren ? ', true' : '') . ');';
                    else return '';
                }
                case 'toggleClasses': {
                    $classes = $this->generateClasses($modify['classes']);
                    if ($modify["view"]) {
                        $view = $this->reduceView($modify["view"]);
                        if ($view) return $this->createViewSelector($view) . '.toggleClass(' . $classes . ');';
                        else return '';
                    } else {
                        $c = 'if (typeof res === "undefined") {var res = $(result);}';
                        return $c . 'res.toggleClass(' . $classes . ');';
                    }
                }
                case 'mousePosition': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return '(function() {' . 'ms(' . $this->createViewSelector($view) . ', e);' . '})();';
                    else return '';
                }
                case 'openWindow': {
                    $protocol = $modify["protocol"]; // It is a template object
                    $view = $modify["view"];
                    if ($protocol instanceof TemplateObject) {
                        $handler = 'callFunc(' . $this->asContext($protocol) . ', ' .
                            '(typeof result !== "undefined") ? result : undefined)';
                    } else $handler = null;
                    $url = $this->asContext($modify["url"], null, true);
                    $name = $this->asContext($modify["name"], null, true);

                    if ($view) {
                        $view = $this->reduceView($modify["view"]);
                        $view = $this->createViewSelector($view);
                    }
                    return 'openWindow(' . $url . ', ' . $name . ', ' .
                        ($handler ? 'function() {' . $handler . '}' : '() => {}') . ', ' .
                        ($view ? $view . '.html()' : 'null') . ');';
                }
                case 'function': {
                    $code = $modify["code"];
                    if ($code instanceof TemplateObject) return 'callAnFunc(con, "' . $code->getName() . '", result);';
                    else return $code;
                }
                case 'retain': {
                    $view = $modify["view"];
                    if ($view instanceof TemplateObject) {
                        $template = $view;
                    } else $template = null;
                    $view = $this->reduceView($modify["view"]);
                    $view = $this->createViewSelector($view);
                    if (!$template) return $view;
                    else return $this->asContext($template) . ' = ' . $view . ';';
                }
                case 'gc': {
                    return 'gc();';
                }
            }
            return "";
        }, $this->protocol));
    }

    /**
     * Reduces the view to a string representation (if available).
     * @param TemplateObject|DirectView|array|string|null $view The view(s).
     * @param bool $class Whether the idc or id is returned.
     * @return TemplateObject|DirectView|array|string|null|false
     */
    private function reduceView(TemplateObject|DirectView|array|string|null $view, bool $class = true):
    TemplateObject|DirectView|array|string|null|false {
        if (is_array($view)) {
            $view = WebshipTools::flatten(array_filter(array_map(function ($v) use ($class) {
                return $this->reduceSingleView($v, $class);
            }, $view), function ($v) {
                return (bool)$v;
            }));
            if (count($view) > 0) return ($class ? implode(',', $view) : $view);
            return false;
        } else return $this->reduceSingleView($view, $class);
    }

    /**
     * Reduces a single view to a string representation.
     * @param TemplateObject|DirectView|array|string|null $view The view.
     * @param bool $class Whether the view or its class/id should be returned.
     * @return TemplateObject|DirectView|array|string|null|false
     */
    private function reduceSingleView(TemplateObject|DirectView|array|string|null $view, bool $class = true):
    TemplateObject|DirectView|array|string|null|false {
        if (is_string($view)) {
            $view = $this->innerView()->get($view, true); // true is important!
            if ($view !== null) $view = $view["view"];
        } else if (is_object($view) && !($view instanceof DirectView) && !($view instanceof TemplateObject) && $this->innerView()) {
            $view = $this->innerView()->pick($view);
        }
        if ($view !== null) {
            if ($view instanceof TemplateObject) {
                $view->setIsView(true);
                return $view;
            } else if (is_array($view)) {
                return array_map(function ($v) use ($class) {
                    return ($class ? $v->idc() : $v);
                }, $view);
            } else return ($class ? $view->idc() : $view);
        } else return false;
    }

    /**
     * Create the view(s) selector.
     * @param TemplateObject|array|string $view The view(s).
     * @return string
     */
    private function createViewSelector(TemplateObject|array|string $view) : string {
        if (is_string($view)) return '$("' . $view . '")';
        if (is_array($view)) return '$("' . rtrim(implode(',', array_map(function ($v) {
                return $this->createInnerViewSelector($v);
            }, $view)), ' + "') . '")';
        if ($view instanceof TemplateObject) return '$(asSel(' . $this->asContext($view) . '))';
        return '';
    }

    /**
     * Almost the same as {@link HtmlModifyProtocol::createViewSelector()} but handling {@link TemplateObject}
     * differently.
     * @param TemplateObject|array|string $view The view.
     * @return string
     */
    private function createInnerViewSelector(TemplateObject|array|string $view) : string {
        if (is_string($view)) return $view;
        if (is_array($view)) return implode(',', array_map(function ($v) {
            return $this->createInnerViewSelector($v);
        }, $view));
        if ($view instanceof TemplateObject) return '" + ' . $this->asContext($view) . ' + "';
        return '';
    }

    /**
     * Creates an array of ids or access tokens out of an array of objects or a single object.
     * @param IShippable|TemplateObject|array $objects The object(s).
     * @return string[]
     */
    private function createObjectIdList(IShippable|TemplateObject|array $objects) : array {
        if (!is_array($objects)) $objects = [$objects];
        return array_map(function (IShippable|TemplateObject $object) : string {
            if ($object instanceof IShippable) {
                $objId = ParameterSerializer::handleObject($object);
                $objId = '"' . $objId["IClass"] . $objId["value"] . '"';
            } else {
                $objId = $this->asContext($object, 'IClass') . ' + ' . $this->asContext($object);
            }
            return $objId;
        }, $objects);
    }

    /**
     * Handles an event if it is not given - just a template.
     * @param TemplateObject|string|int $event The event.
     * @return string
     */
    private function handleEventType(TemplateObject|string|int $event) : string {
        return $this->asContext($event);
    }

    /**
     * Generate the dom action.
     * @param array $modify The associative array with information.
     * @return string
     * @throws DatabaseError
     */
    private function generateDomAction(array $modify) : string {
        // Although not beautiful, this code section is required to handle unknown variables, which cannot be
        // passed to a function in JS, unfortunately.
        // The best solution is to provide DOM actions in a template protocol.
        $c = 'if (typeof(res) === "undefined") {var res = ';
        if (!array_key_exists("new", $modify)) {
            if (!array_key_exists("value", $modify)) $c .= 'result';
            else $c .= '"' . $modify["value"] . '"';
        } else {
            $c .= '$(';
            $new = $modify["new"];
            if (!$new || $new === null) {
                $c .= 'result';
            } else if ($new instanceof View) {
                $c .= '\'' . $new->generate();
                if ($new instanceof HtmlView) {
                    $c .= '<style type="text/css">' . $new->generateStyle() . '</style>' .
                        '<script>' . $new->generateJS() . '</script>';
                }
                $c .= '\'';
            }
            $c .= ')';
        }
        $c .= ';}';

        // The actual code to perform the dom action.
        // *Replace* is a special case since classes have to be copied.
        $view = $this->reduceView($modify["view"]);
        if (!$view && $modify["operation"] === 'addWithFallback') $view = $this->reduceView($modify["fallback"]);
        if ($view) {
            $sel = $this->createViewSelector($view);
            if ($modify["operation"] !== 'replace') $c .= $sel . '.';
            else return $c . 'replaceDom(' . substr($sel, 1) . ', res, ' . Event::DELETE . ');';
            switch ($modify["operation"]) {
                case 'after':
                    $c .= 'after';
                    break;
                case 'before':
                    $c .= 'before';
                    break;
                case 'addWithFallback':
                    {
                        $fallback = $this->reduceView($modify["fallback"]);
                        if ($fallback) $c .= 'existsGetElse(() => { return ' . $this->createViewSelector($fallback) . '; }).';
                        $c .= 'append';
                    }
                    break;
                case 'add':
                    $c .= 'append';
                    break;
                case 'prepend':
                    $c .= 'prepend';
                    break;
                case 'delete':
                    $c .= 'trigger("' . Event::DELETE . '").remove';
                    break;
                case 'text':
                    $c .= 'html';
                    break;
            }
            $c .= '(domAction(res' . ($modify["operation"] === 'text' ? ', true' : '') . '));';
        }
        return $c;
    }

    /**
     * Represents the template object as context access.
     * @param TemplateObject|string $object The template object (or a string).
     * @param string|null $extension An extension being applied to the name of the template.
     * @param bool $stringWithTags If a given string shall be used with tags.
     * @return string
     */
    public function asContext(TemplateObject|string $object, ?string $extension = null,
                              bool                  $stringWithTags = false) : string {
        if ($object instanceof TemplateObject) {
            if (is_null($extension)) $extension = '';
            return 'con["' . $object->getName() . $extension . '"]';
        } else if (!$stringWithTags) return $object;
        else return '"' . $object . '"';
    }

    /**
     * Generate the information about data.
     * @param TemplateObject|array|null $dataInfo The data information (if available).
     * @return string
     */
    private function generateData(TemplateObject|array|null $dataInfo) : string {
        if ($dataInfo) {
            if (!($dataInfo instanceof TemplateObject)) {
                return ', ' . WebshipTools::jsonEncode(array_reduce(array_keys($dataInfo),
                        function (array $values, $param) use ($dataInfo) {
                            $value = $dataInfo[$param];
                            if ($value instanceof CliffSerializable) {
                                $value = ParameterSerializer::handleObject($value);
                                $values[$param] = $value["value"];
                                $values[$param . 'IClass'] = $value["IClass"];
                            } else {
                                $values[$param] = $value;
                            }
                            return $values;
                        }, []));
            } else {
                return ', ' . $this->asContext($dataInfo);
            }
        }
        return '';
    }

    /**
     * Generates a string representation of the scope(s).
     * @param TemplateObject|array|null $scopesInfo The scope information.
     * @return string
     */
    private function generateScopes(TemplateObject|array|null $scopesInfo) : string {
        if ($scopesInfo) {
            if (!($scopesInfo instanceof TemplateObject)) {
                return ', ' . WebshipTools::jsonEncode($scopesInfo);
            } else {
                return ', ' . $this->asContext($scopesInfo);
            }
        }
        return '';
    }

    /**
     * Generates a JS code fragment for classes.
     * @param array $classes An array of class-representing objects (string, TemplateObject).
     * @param string|null $join The string that is used in JS to join the classes.
     * @return string
     */
    private function generateClasses(array $classes, ?string $join = ' ') : string {
        return '[' . implode(',', array_filter(array_map(function ($c) : ?string {
                if (is_string($c)) return '"' . $c . '"';
                else if ($c instanceof TemplateObject) {
                    return $this->asContext($c);
                }
                return null;
            }, $classes), 'is_string')) . ']' . (is_null($join) ? '' : '.join("' . $join . '")');
    }
}
?>