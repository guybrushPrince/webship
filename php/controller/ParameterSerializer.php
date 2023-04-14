<?php
/**
 * Class ParameterSerializer
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ParameterSerializer {

    /**
     * Serialize the parameters.
     * @param Protocol $protocol The protocol.
     * @param array $definitions The defined parameters.
     * @param array $parameters The values of the parameters.
     * @param DirectView|null $entry The entry / context view.
     * @return array|string[]
     */
    public static function serialize(Protocol $protocol, array $definitions, array $parameters, DirectView $entry = null) : array {
        $data = Tools::flatten(array_map(function($definition) use ($protocol, $parameters, $entry) {
            if ($definition instanceof ReflectionParameter) $param = $definition->getName();
            else if ($definition instanceof TemplateObject) {
                if ($definition->isView()) $param = $definition;
                else $param = $definition->getName();
            }
            else return '';
            // Try to find the value in the views if the parameter is not set directly.
            $name = ($param instanceof TemplateObject ? $param->getName() : $param);
            if (!array_key_exists($name, $parameters)) {
                return ParameterSerializer::findValueInView($protocol, $param, $entry);
            } else {
                return ParameterSerializer::serializeValue($protocol, $param, $parameters[$name], $entry, $parameters);
            }
        }, $definitions));
        return $data;
    }

    /**
     * Find the value in the views if the parameter is not set directly.
     * @param Protocol $protocol The protocol.
     * @param string|TemplateObject $param The required parameter.
     * @param DirectView|null $entry The entry / context view.
     * @return string
     */
    private static function findValueInView(Protocol $protocol, $param, ?DirectView $entry = null) : string {
        if ($param instanceof TemplateObject) $param = $param->getName();
        if (!$protocol->innerView()) $innerView = $entry;
        else $innerView = $protocol->innerView();
        if ($innerView !== null) {
            $info = $innerView->get($param);
            if ($info !== null) {
                $view = $info["view"];
                $scope = $info["scope"];
                $selector = "";
                if ($view instanceof View) {
                    $selector .= '$("' . $view->idc() . '")';
                    if ($scope === "value") {
                        if ($view instanceof InputHtmlView && $view->type() === 'checkbox') {
                            $selector .= '.is(":checked")';
                        /*} else if ($view instanceof EditableHtmlView) {
                            $selector .= '.html()';*/
                        } else if ($view instanceof CodeEditorHtmlView) {
                            $selector = 'cm' . $view->id() . '.getValue()';
                        } else {
                            $selector .= '.val()';
                        }
                    } else if (!$scope) {
                        $selector .= '.attr("' . $scope . '")';
                    }
                }
                return $param . ': ' . $selector;
            } else return $param . ': undefined';
        } else {
            // There is no view, we have to assume that the specific parameter will be set from the thrower.
            // Create a new template object for it.
            $obj = TemplateObject::_new($param);
            $protocol->addTemplateObject($obj);
            return $param . ': con["' . $obj->getName() . '"]';
        }
    }

    /**
     * Serialize the value.
     * @param Protocol $protocol The protocol.
     * @param string|TemplateObject $param The parameter.
     * @param string|array|bool|Context|CObject $value The value.
     * @param DirectView|null $entry The entry / context view.
     * @param array $parameters The parameters.
     * @return string|array
     */
    private static function serializeValue(Protocol $protocol, $param, $value, ?DirectView $entry, array $parameters) {
        if ($param instanceof TemplateObject) {
            $isView = $param->isView();
            $param = $param->getName();
        } else $isView = false;
        if (is_array($value)) {
            return $param . ': {' . implode(',', array_map(function ($key) use ($protocol, $value, $entry) {
                    $field = $value[$key];
                    if (!$protocol->innerView()) $innerView = $entry;
                    else $innerView = $protocol->innerView();
                    if ($innerView) {
                        $info = $innerView->get($key);
                    }
                    else $info = false;
                    if ($info) {
                        $view = $info["view"];
                        $scope = $info["scope"];
                        $selector = "";
                        if ($view instanceof View) {
                            $selector .= '$("' . $view->idc() . '")';
                            if ($scope === "value") {
                                if ($view instanceof InputHtmlView && $view->type() === 'checkbox') {
                                    $selector .= '.is(":checked")';
                                } else {
                                    $selector .= '.val()';
                                }
                            } else if (!$scope) {
                                $selector .= '.attr("' . $scope . '")';
                            }
                        }
                        return $key . ': ' . $selector;
                    } else {
                        if (is_int($key)) $key = 'i' . $key;
                        else $key = str_replace(' ', '__WS__', $key);
                        if (is_string($field)) $field = '"' . $field . '"';
                        else if (is_bool($field)) $field = ($field ? 'true' : 'false');
                        else if ($field instanceof View) $field = '"' . $field->idc() . '"';
                        else if (is_object($field)) $field = Tools::jsonEncode(self::handleObject($field));
                        else if (is_array($field)) $field = Tools::jsonEncode(self::preserveArrayKeys($field));
                        return $key . ': ' . $field;
                    }
                }, array_keys($value))) . '}';
        } else if ($value instanceof Context) {
            return $param . ': ' . $value->generate();
        } else if ($value instanceof View) {
            return $param . ': "' . $value->idc() . '"';
        } else if ($value instanceof TemplateObject) {
            $ifClass = $param . 'IClass';
            $ifClassVal = $value->getName() . 'IClass';
            return [$param . ': con["' . $value->getName() . '"]',
                $ifClass . ': ("' . $ifClassVal . '" in con ? con["' . $ifClassVal . '"] : undefined)'];
        } else if ($value instanceof Protocol) {
            $reducedParameters = array_reduce(array_keys($parameters), function($pm, $p) use ($param, $parameters) {
                if ($p !== $param) $pm[$p] = $parameters[$p];
                return $pm;
            }, array());
            if (!$protocol->innerView()) $innerView = $entry;
            else $innerView = $protocol->innerView();
            return $param . ': function(result = undefined) {' . $value->generate($reducedParameters, $innerView) . '}';
        } else if (is_object($value)) {
            return self::handleObject($value, $param);
        } else if ($value === null) {
            return $param . ': null';
        } else if (is_string($value)) {
            if (!$isView) return $param . ': ' . '"' . $value . '"';
            else {
                if (!$protocol->innerView()) $innerView = $entry;
                else $innerView = $protocol->innerView();
                $info = $innerView->get($value, true);
                if ($info) return $param . ': "' . $info["view"]->idc() . '"';
                else return $param . ': ""';
            }
        } else {
            return $param . ': ' . (is_bool($value) ? ($value ? 'true' : 'false') : $value);
        }
    }

    /**
     * Handles an object.
     * @param object $value The object.
     * @param string|null $param The parameter.
     * @return string[]
     */
    public static function handleObject(object $value, ?string $param = null) : array {
        $key = $value->getKey();
        // Convert it
        $key = (is_integer($key) ? ActionRegister::convertKey($key) : $key);
        // Convert the type of the object
        $type = get_class($value);
        if ($param !== null) {
            return [$param . ': ' . (is_string($key) ? '"' . $key . '"' : $key),
                $param . 'IClass' . ': "' . $type . '"'];
        } else {
            return ['value' => $key, 'IClass' => $type];
        }
    }

    /**
     * Collects the fields that are bounded as reference.
     * @param array $fields The fields.
     * @return array
     */
    public static function collect(array $fields = []) {
        return array_fill_keys($fields, $fields);
    }

    /**
     * Preserves the keys of an array.
     * @param array $a The array.
     * @return array
     */
    private static function preserveArrayKeys(array $a) : array {
        return array_reduce(array_keys($a), function (array $c, $k) use ($a) {
            $c[is_int($k) ? 'i' . $k : str_replace(' ', '__WS__', $k)] = (is_array($a[$k]) ? self::preserveArrayKeys($a[$k]) : $a[$k]);
            return $c;
        }, []);
    }
}
?>