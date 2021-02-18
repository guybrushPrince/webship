<?php

include_once dirname(__FILE__) . '/../../../ship/php/ModifyProtocol.php';

/**
 * Class HtmlModifyProtocol
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlModifyProtocol extends ModifyProtocol {

    /**
     * @inheritDoc
     */
    public function __construct($parent) {
        parent::__construct($parent, "HtmlActionProtocol");
    }

    /**
     * @inheritDoc
     */
    public function generate() : string {
        $code = '';
        $code .= implode(array_map(function(array $modify) {
            switch ($modify["operation"]) {
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
                        return 'con["' . $anonymousProtocol->getName() . '"](result);';
                    } else return '';
                }
                case 'setCookie': {
                    $c = 'let d = new Date();';
                    if ($modify["value"] !== '') {
                        $c .= 'd.setTime(d.getTime() + 86400);';
                    }
                    $c .= 'document.cookie = ' .
                        ($modify["value"] ? $modify["value"] : 'result') .
                        ' + ";expires=" + d.toUTCString() + ";path=/"' . ';';
                    return $c;
                }
                case 'set': {
                    if ($modify["value"] && $modify["value"] instanceof View) {
                        $scope = $modify["scope"];
                        $selector = '$("' . $modify["value"]->idc() . '")';
                        if ($scope === "value") {
                            $selector .= '.val()';
                        } else if (!$scope) {
                            $selector .= '.attr("' . $scope . '")';
                        }
                        $modify["value"] = $selector;
                    } else {
                        $modify["value"] = (is_string($modify["value"]) ? '"' . $modify["value"] . '"' : $modify["value"]);
                    }
                    return 'context.' . $modify["key"] . ' = ' .
                        ($modify["value"] ?
                            $modify["value"]
                            : 'result') . ';';
                }
                case 'event': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return $this->createViewSelector($view) . '.trigger("' . $modify["event"] . '");';
                    } else {
                        return '';
                    }
                }
                case 'get': {
                    return 'context.' . $modify["key"] . ';';
                }
                case 'delete': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return $this->createViewSelector($view) . '.remove();';
                    } else {
                        return '';
                    }
                }
                case 'resize': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return 'try { document.getElementsByClassName("' . substr($view, 1) . '")[0].onload = function() { ' .
                            '$(this).height($(this).contents().find("div[id]").height() + 20);' .
                            '$(this).contents().find("body").css("overflow-y", "hidden");' .
                            '}; } catch (e) {}' .
                            'try { let x = $("' . $view . '");' .
                            'x.height(x.contents().find("div[id]").height() + 20);' .
                            'x.contents().find("body").css("overflow-y", "hidden");' .
                            '} catch (ex) {}';
                    } else {
                        return '';
                    }
                }
                case 'reheight': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        $code = 'let v = ' . $this->createViewSelector($view) . ';';
                        $code .= 'let pt = v.css("padding-top");';
                        $code .= 'let pb = v.css("padding-bottom");';
                        $code .= 'pt = parseInt(pt.substr(0, pt.length - 2));pb = parseInt(pb.substr(0, pb.length - 2));';
                        $code .= 'v.height(v.prop("scrollHeight") - (pt + pb));';
                        return $code;
                    } else return '';
                }
                case 'reset': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return $this->createViewSelector($view) . '.removeAttr("style");';
                    } else return '';
                }
                case 'focus': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return $this->createViewSelector($view) . '.focus();';
                    } else return '';
                }
                case 'add':
                case 'replace':
                case 'before':
                case 'after':
                case 'text': {
                    return $this->generateDomAction($modify);
                }
                case 'throw': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        $data = '';
                        if ($modify["data"]) $data = ', ' . Tools::jsonEncode($modify["data"]);
                        return $this->createViewSelector($view) . '.trigger("' . $modify["event"] . '"' . $data . ');';
                    } else {
                        return '';
                    }
                }
                case 'clear': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return $this->createViewSelector($view) . '.each((i,d) => d.reset());';
                    } else {
                        return '';
                    }
                } break;
                case 'addClasses': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return $this->createViewSelector($view) . '.addClass("' . implode(' ', $modify["classes"]) . '");';
                    } else {
                        return '';
                    }
                } break;
                case 'removeClasses': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return $this->createViewSelector($view) . '.removeClass("' . implode(' ', $modify["classes"]) . '");';
                    } else {
                        return '';
                    }
                } break;
                case 'removeClassesSiblings': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        return $this->createViewSelector($view) . '.find(".' . implode(',.', $modify["classes"]) . '").removeClass("' . implode(' ', $modify["classes"]) . '");';
                    } else {
                        return '';
                    }
                } break;
                case 'toggleClasses': {
                    $c = 'if (typeof res === "undefined") {var res = $(result);}';
                    if ($modify["view"]) {
                        $view = $this->reduceView($modify["view"]);
                        if ($view) {
                            return $this->createViewSelector($view) . '.toggleClass("' . implode(' ', $modify["classes"]) . '");';
                        } else {
                            return '';
                        }
                    } else {
                        return $c . 'res.toggleClass("' . implode(' ', $modify["classes"]) . '");';
                    }
                } break;
                case 'mousePosition': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        $code = '(function() {';
                        $code .= 'ms(' . $this->createViewSelector($view) . ', e);';
                        /*$code .= 'let s = ' . $this->createViewSelector($view) . ';';
                        $code .= 'let w = s.width(), d = $(document).width();';
                        $code .= 'let l = e.pageX + $(e.target).scrollTop();';
                        $code .= 'l = (l + w > d ? d - w : l);';
                        $code .= 's' .
                            '.css("top", e.pageY + $(e.target).scrollTop())' .
                            '.css("left", l);';*/
                        $code .= '})();';
                        return $code;
                    } else {
                        return '';
                    }
                } break;
                /*case 'each': {
                    $array = $modify["array"];
                    $array = array_map(function($el) {
                        if (is_string($el)) $el = $this->innerView()->get($el, true)["view"];
                        if ($el !== null) return $el->idc();
                        else return "";
                    }, $array);
                    return '$("' . implode(',', $array) . '").each(function(i,e)"' . implode(' ', $modify["classes"]) . '");';
                } break;*/
            }
            return "";
        }, $this->protocol));

        return $code;
    }

    /**
     * Reduces the view to a string representation (if available)
     * @param string|array|DirectView $view The view(s).
     * @param boolean $class Whether the idc or id is returned.
     * @return array|DirectView|bool|string
     */
    private function reduceView($view, $class = true) {
        if (is_array($view)) {
            $view = array_filter(array_map(function($v) use ($class) {
                return $this->reduceSingleView($v, $class);
            }, $view), function($v) { return ($v ? true: false);});
            if (count($view) > 0) return ($class ? implode(',', $view) : $view);
            return false;
        } else return $this->reduceSingleView($view, $class);
    }

    /**
     * Reduces a single view to a string representation.
     * @param string|DirectView $view The view.
     * @param bool $class Whether the view or its class/id should be returned.
     * @return bool|DirectView|string
     */
    private function reduceSingleView($view, $class = true) {
        $tmp_view = $view;
        if (is_string($view)) {
            $view = $this->innerView()->get($view, true); // true is important!
            if ($view !== null) $view = $view["view"];
        }
        if ($view !== null) {
            if ($view instanceof TemplateObject) {
                $view->setIsView(true);
                return $view;
            } else return ($class ? $view->idc() : $view);
        }
        else return false;
    }

    /**
     * Create the view selector.
     * @param array|string|TemplateObject $view The view.
     * @return string
     */
    private function createViewSelector($view) {
        if (is_string($view)) return '$("' . $view . '")';
        if (is_array($view)) return '$("' . rtrim(implode(',', array_map(function($v) {
            return $this->createInnerViewSelector($v);
        }, $view)), ' + "') . '")';
        if ($view instanceof TemplateObject) return '$(con["' . $view->getName() . '"])';
        return '';
    }

    /**
     * Create an inner view selector.
     * @param array|string|TemplateObject $view The view.
     * @return string
     */
    private function createInnerViewSelector($view) {
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
     */
    private function generateDomAction($modify) {
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

        $view = $this->reduceView($modify["view"], false);
        if ($view) {
            if (!is_array($view)) $view = array($view);
            if ($modify["operation"] === 'replace') {
                $c .= 'res.filter(":not(style):not(script)").addClass(("' .
                    implode(' ', array_map(function($v) {
                        if ($v instanceof View) return $v->id();
                        else return '" + con["' . $v->getName() . '"] + "';
                    }, $view)) . '").replace(/\./g, ""));';
            }
            $c .= '$("' . implode(',', array_map(function($v) {
                if ($v instanceof View) return $v->idc();
                else return '" + con["' . $v->getName() . '"] + "';
            }, $view)) . '").';
            switch ($modify["operation"]) {
                case 'after':
                    $c .= 'after';
                    break;
                case 'before':
                    $c .= 'before';
                    break;
                case 'replace':
                    $c .= 'replaceWith';
                    break;
                case 'add':
                    $c .= 'append';
                    break;
                case 'delete':
                    $c .= 'remove';
                    break;
                case 'text':
                    $c .= 'html';
                    break;
            }
            $c .= '(';
            $c .= 'res';
            $c .= ');';
        }
        return $c;
    }

}
?>