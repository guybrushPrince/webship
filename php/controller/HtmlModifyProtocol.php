<?php

include_once __DIR__ . '/../../../ship/php/ModifyProtocol.php';

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
     * @inheritDoc
     */
    public function generate() : string {
        return implode(array_map(function(array $modify) {
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
                        $f = 'con["' . $anonymousProtocol->getName() . '"]';
                        return 'callFunc(' . $f . ', result);';
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
                        $val = 'con[\'' . $val->getName() . '\']';
                    } else if ($val instanceof IShippable) {
                        $val = '"' . ShipperRegister::getObjectString($val) . '"';
                    } else {
                        $val = (is_string($val) ? '"' . $val . '"' : $val);
                    }
                    return 'context.' . $modify["key"] . ' = ' . $val . ';';
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
                        $event = $modify["event"];
                        $sel = $this->createViewSelector($view);
                        if ($event instanceof TemplateObject) $event = '" + con["' . $event->getName() . '"] + "';
                        return 'triggerEvent(' . $sel . ', "' . $event . '"' . $data . ');';
                    } else {
                        return '';
                    }
                }
                case 'throwObjectEvent': {
                    $obj = $modify["object"];
                    if ($obj instanceof IShippable || $obj instanceof TemplateObject || (is_array($obj) && !Tools::isEmpty($obj))) {
                        $event = $modify["event"];
                        if ($event instanceof TemplateObject) $event = '" + con["' . $event->getName() . '"] + "';

                        $scopesInfo = $modify["scopes"];
                        $scopes = '';
                        if ($scopesInfo) {
                            if (!($scopesInfo instanceof TemplateObject)) {
                                $scopes = ', ' . Tools::jsonEncode($scopesInfo);
                            } else {
                                $scopes = ', con["' . $scopesInfo->getName() . '"]';
                            }
                        }

                        $data = $this->generateData($modify["data"]);
                        if (!is_array($obj)) $obj = [ $obj ];
                        $objId = implode(',', array_map(function (IShippable|TemplateObject $object) : string {
                            if ($object instanceof IShippable) {
                                $objId = ParameterSerializer::handleObject($object);
                                $objId = '"' . $objId["IClass"] . $objId["value"] . '"';
                            } else {
                                $objId = 'con["' . $object->getName() . 'IClass"] + con["' . $object->getName() . '"]';
                            }
                            return $objId;
                        }, $obj));

                        return 'eventBus.trigger([' . $objId . '], "' . $event . '", _i_n_' . $scopes . $data . ');';
                    } else {
                        return '';
                    }
                }
                case 'unbindObjectEvent': {
                    $obj = $modify["object"];
                    if ($obj instanceof IShippable || $obj instanceof TemplateObject || (is_array($obj) && !Tools::isEmpty($obj))) {
                        $event = $modify["event"];
                        if ($event instanceof TemplateObject) $event = '" + con["' . $event->getName() . '"] + "';

                        $scopesInfo = $modify["scopes"];
                        $scopes = '';
                        if ($scopesInfo) {
                            if (!($scopesInfo instanceof TemplateObject)) {
                                $scopes = ', ' . Tools::jsonEncode($scopesInfo);
                            } else {
                                $scopes = ', con["' . $scopesInfo->getName() . '"]';
                            }
                        }

                        if (!is_array($obj)) $obj = [ $obj ];
                        $objId = implode(',', array_map(function (IShippable|TemplateObject $object) : string {
                            if ($object instanceof IShippable) {
                                $objId = ParameterSerializer::handleObject($object);
                                $objId = '"' . $objId["IClass"] . $objId["value"] . '"';
                            } else {
                                $objId = 'con["' . $object->getName() . 'IClass"] + con["' . $object->getName() . '"]';
                            }
                            return $objId;
                        }, $obj));

                        $view = $this->reduceView($modify["view"]);
                        return 'eventBus.unbind(' . $this->createViewSelector($view) . ', [' . $objId . '], "' . $event . '"' . $scopes . ');';
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
                        $classes = array_map(function($c) {
                            if (is_string($c)) return $c;
                            else if ($c instanceof TemplateObject) {
                                return '" + con["' . $c->getName() . '"] + "';
                            }
                        }, $modify["classes"]);
                        return $this->createViewSelector($view) . '.addClass("' . implode(' ', $classes) . '");';
                    } else {
                        return '';
                    }
                }
                case 'removeClasses': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        $classes = array_map(function($c) {
                            if (is_string($c)) return $c;
                            else if ($c instanceof TemplateObject) {
                                return '" + con["' . $c->getName() . '"] + "';
                            }
                        }, $modify["classes"]);
                        return $this->createViewSelector($view) . '.removeClass("' . implode(' ', $classes) . '");';
                    } else {
                        return '';
                    }
                }
                case 'empty': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) return $this->createViewSelector($view) . '.empty();';
                    else return '';
                }
                case 'removeClassesSiblings': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        $classes = array_map(function($c) {
                            if (is_string($c)) return $c;
                            else if ($c instanceof TemplateObject) {
                                return '" + con["' . $c->getName() . '"] + "';
                            }
                        }, $modify["classes"]);
                        return $this->createViewSelector($view) . '.find(".' . implode(',.', $classes) . '").removeClass("' . implode(' ', $classes) . '");';
                    } else {
                        return '';
                    }
                }
                case 'toggleClasses': {
                    $c = 'if (typeof res === "undefined") {var res = $(result);}';
                    $classes = implode(' ', array_map(function($c) {
                        if (is_string($c)) return $c;
                        else if ($c instanceof TemplateObject) {
                            return '" + con["' . $c->getName() . '"] + "';
                        }
                    }, $modify["classes"]));
                    if ($modify["view"]) {
                        $view = $this->reduceView($modify["view"]);
                        if ($view) {
                            return $this->createViewSelector($view) . '.toggleClass("' . $classes . '");';
                        } else {
                            return '';
                        }
                    } else {
                        return $c . 'res.toggleClass("' . $classes . '");';
                    }
                }
                case 'mousePosition': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        $code = '(function() {';
                        $code .= 'ms(' . $this->createViewSelector($view) . ', e);';
                        $code .= '})();';
                        return $code;
                    } else {
                        return '';
                    }
                }
                case 'openWindow': {
                    $url = $modify["url"];
                    $name = $modify["name"];
                    $protocol = $modify["protocol"]; // It is a template object
                    $view = $modify["view"];
                    if ($protocol instanceof TemplateObject) {
                        $f = 'con["' . $protocol->getName() . '"]';
                        $handler = 'result = (typeof result !== "undefined") ? result : undefined;' .
                            'if (isFunction(' . $f . ')) ' . $f . '(result);';
                    } else $handler = null;
                    if ($url instanceof TemplateObject) {
                        $url = '" + con["' . $url->getName() . '"] + "';
                    }
                    if ($name instanceof TemplateObject) {
                        $name = '" + con["' . $name->getName() . '"] + "';
                    }
                    if ($view) {
                        $view = $this->reduceView($modify["view"]);
                        $view = $this->createViewSelector($view);
                    }
                    return 'openWindow("' . $url . '", "' . $name . '", ' . ($handler ? 'function() {' . $handler . '}' : '() => {}') . ', ' .
                        ($view ? $view . '.html()' : 'null' ) . ');';
                }
                case 'function': {
                    $code = $modify["code"];
                    if ($code instanceof TemplateObject) {
                        $code = '(function(con) { let f = con["' . $code->getName() . '"]; ' .
                            'if (isString(f)) eval(f); else f(); })(con);';
                    }
                    return $code;
                }
                case 'retain': {
                    $view = $modify["view"];
                    if ($view instanceof TemplateObject) {
                        $name = $view->getName();
                    } else $name = null;
                    $view = $this->reduceView($modify["view"]);
                    $view = $this->createViewSelector($view);
                    if (!$name) return $view;
                    else return 'con["' . $name . '"] = ' . $view . ';';
                }
                case 'gc': {
                    return 'gc();';
                }
            }
            return "";
        }, $this->protocol));
    }

    /**
     * Reduces the view to a string representation (if available)
     * @param string|array|DirectView $view The view(s).
     * @param boolean $class Whether the idc or id is returned.
     * @return array|DirectView|bool|string
     */
    private function reduceView($view, $class = true) {
        if (is_array($view)) {
            $view = Tools::flatten(array_filter(array_map(function($v) use ($class) {
                return $this->reduceSingleView($v, $class);
            }, $view), function($v) { return ($v ? true: false);}));
            if (count($view) > 0) return ($class ? implode(',', $view) : $view);
            return false;
        } else return $this->reduceSingleView($view, $class);
    }

    /**
     * Reduces a single view to a string representation.
     * @param string|DirectView $view The view.
     * @param bool $class Whether the view or its class/id should be returned.
     * @return bool|DirectView|string|string[]
     */
    private function reduceSingleView($view, $class = true) {
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
        }
        else return false;
    }

    /**
     * Create the view(s) selector.
     * @param TemplateObject|array|string $view The view(s).
     * @return string
     */
    private function createViewSelector(TemplateObject|array|string $view) : string {
        if (is_string($view)) return '$("' . $view . '")';
        if (is_array($view)) return '$("' . rtrim(implode(',', array_map(function($v) {
            return $this->createInnerViewSelector($v);
        }, $view)), ' + "') . '")';
        if ($view instanceof TemplateObject) return '$(asSel(con["' . $view->getName() . '"]))';
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
        if (is_array($view)) return implode(',', array_map(function($v) {
            return $this->createInnerViewSelector($v);
        }, $view));
        if ($view instanceof TemplateObject) return '" + con["' . $view->getName() . '"] + "';
        return '';
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
                case 'addWithFallback': {
                    $fallback = $this->reduceView($modify["fallback"]);
                    if ($fallback) $c .= 'existsGetElse(() => { return ' . $this->createViewSelector($fallback) . '; }).';
                    $c .= 'append';
                } break;
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
     * Generate the information about data.
     * @param TemplateObject|array|null $dataInfo The data information (if available).
     * @return string
     */
    private function generateData(TemplateObject|array|null $dataInfo) : string {
        if ($dataInfo) {
            if (!($dataInfo instanceof TemplateObject)) {
                return ', ' . Tools::jsonEncode(array_reduce(array_keys($dataInfo),
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
                return ', con["' . $dataInfo->getName() . '"]';
            }
        }
        return '';
    }

}
?>