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
        parent::__construct($parent, "HtmlActionProtocol", "HtmlDialogProtocol");
    }

    /**
     * Create a new html modify protocol.
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
                        $f = 'con["' . $anonymousProtocol->getName() . '"]';
                        return 'result = (typeof result !== "undefined") ? result : undefined;' .
                            'if (isFunction(' . $f . ')) ' . $f . '(result);';
                    } else return '';
                }
                case 'alert': {
                    return $modify["protocol"]->generate();
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
                case 'reload': {
                    return 'location.reload();';
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
                        return $this->createViewSelector($view) . '.trigger("' . Event::DELETE . '").remove();';
                    } else {
                        return '';
                    }
                }
                case 'resize': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        if ($view instanceof TemplateObject) $view = 'con[\'' . $view->getName() . '\'].substring(1)';
                        else $view = '"' . substr($view, 1) . '"';
                        return 'try { document.getElementsByClassName(' . $view . ')[0].onload = function() { ' .
                            '$(this).height($(this).contents().find("div[id]").height() + 20);' .
                            '$(this).contents().find("body").css("overflow-y", "hidden");' .
                            '}; } catch (e) { }' .
                            'try { let x = $(\'.\' + ' . $view . ');' .
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
                        return 'reheight(' . $this->createViewSelector($view) . ');';
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
                case 'value': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        $value = $modify["value"];
                        if ($value instanceof TemplateObject) {
                            $name = $value->getName();
                            $mayClass = $name . 'IClass';
                            $value = '" + ("' . $mayClass . '" in con ? con["' . $mayClass . '"] + "<::>" : "") + con["' . $name . '"] + "';
                        }
                        return $this->createViewSelector($view) . '.val("' . $value . '");';
                    } else return '';
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
                case 'throw': {
                    $view = $this->reduceView($modify["view"]);
                    if ($view) {
                        $data = '';
                        $dataInfo = $modify["data"];
                        if ($dataInfo) {
                            if (!($dataInfo instanceof TemplateObject)) {
                                $data = ', ' . Tools::jsonEncode(array_reduce(array_keys($dataInfo),
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
                                $data = ', con["' . $dataInfo->getName() . '"]';
                            }
                        }
                        $event = $modify["event"];
                        if ($event instanceof TemplateObject) $event = '" + con["' . $event->getName() . '"] + "';
                        return $this->createViewSelector($view) . '.trigger("' . $event . '"' . $data . ');';
                    } else {
                        return '';
                    }
                }
                case 'throwObjectEvent': {
                    $obj = $modify["object"];
                    if ($obj instanceof CliffSerializable || $obj instanceof TemplateObject) {
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

                        $data = '';
                        $dataInfo = $modify["data"];
                        if ($dataInfo) {
                            if (!($dataInfo instanceof TemplateObject)) {
                                $data = ', ' . Tools::jsonEncode(array_reduce(array_keys($dataInfo),
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
                                $data = ', con["' . $dataInfo->getName() . '"]';
                            }
                        }
                        if ($obj instanceof CliffSerializable) {
                            $objId = ParameterSerializer::handleObject($obj);
                            $objId = $objId["IClass"] . $objId["value"];
                        } else {
                            $objId = '" + con["' . $obj->getName() . 'IClass"] + con["' . $obj->getName() . '"] + "';
                        }
                        return 'eventBus.trigger("' . $objId . '", "' . $event . '", _i_n_' . $scopes . $data . ');';
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
                    if ($view) {
                        return $this->createViewSelector($view) . '.empty();';
                    } else {
                        return '';
                    }
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
                    $code = 'context["windows"].push({' .
                        'window: open("' . $url . '", "' . $name . '", "location=no,toolbar=no,menubar=no,status=no"), ' .
                        'handler: ' . ($handler ? 'function() {' . $handler . '}' : '() => {}') .
                    '});';
                    $code .= 'checkWindowClosed();';
                    return $code;
                }
                case 'function': {
                    $code = $modify["code"];
                    if ($code instanceof TemplateObject) {
                        $code = 'con["' . $code->getName() . '"](result)';
                    }
                    return $code;
                }
                case 'gc': {
                    return 'gc();';
                }
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
     * Create the view selector.
     * @param array|string|TemplateObject $view The view.
     * @return string
     */
    private function createViewSelector($view) {
        if (is_string($view)) return '$("' . $view . '")';
        if (is_array($view)) return '$("' . rtrim(implode(',', array_map(function($v) {
            return $this->createInnerViewSelector($v);
        }, $view)), ' + "') . '")';
        if ($view instanceof TemplateObject) return '$(asSel(con["' . $view->getName() . '"]))';
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
    private function generateDomAction(array $modify) : string {
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
        if (!$view && $modify["operation"] === 'addWithFallback')
            $view = $this->reduceView($modify["fallback"], false);
        if ($view) {
            if (!is_array($view)) $view = array($view);
            if ($modify["operation"] === 'replace') {
                $c .= 'let ids = ' . '[' . implode(',', array_map(function($v) {
                        if ($v instanceof View) return '"' . $v->idc() . '"';
                        else return 'con["' . $v->getName() . '"]';
                    }, $view)) . '].map(i => $(i).data("id")).join(" ");';
                $c .= 'let sel = res.filter(":not(style):not(script)");';
                $c .= 'sel.data("id", sel.data("id") + " " + ids);';
                $c .= 'sel.addClass(("' .
                    implode(' ', array_map(function($v) {
                        if ($v instanceof View) return $v->id();
                        else return '" + con["' . $v->getName() . '"] + "';
                    }, $view)) . ' " + ids).replace(/\./g, ""));';
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
                    $c .= 'trigger("' . Event::DELETE . '").replaceWith';
                    break;
                case 'addWithFallback': {
                    $fallback = $this->reduceView($modify["fallback"], false);
                    if ($fallback) {
                        $sel = '$("' . implode(',', array_map(function ($v) {
                                if ($v instanceof View) return $v->idc();
                                else return '" + con["' . $v->getName() . '"] + "';
                            }, [$fallback])) . '")';
                        $c .= 'existsGetElse(() => { return ' . $sel . '; }).';
                    }
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
            $c .= '(';
            if ($modify["operation"] === 'text') $c .= 'res';
            else $c .= '() => { let r = $(res); $("body").append(r.filter("style")); return r.filter(":not(style)"); }';
            $c .= ');';
        }
        return $c;
    }

}
?>