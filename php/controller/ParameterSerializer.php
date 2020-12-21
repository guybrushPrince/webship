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
     * @param DirectView $entry The entry / context view.
     * @return array|string[]
     */
    public static function serialize(Protocol $protocol, array $definitions, array $parameters, DirectView $entry = null) {
        $data = array_map(function($definition) use ($protocol, $parameters, $entry) {
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
        }, $definitions);
        return $data;
    }

    /**
     * Find the value in the views if the parameter is not set directly.
     * @param Protocol $protocol The protocol.
     * @param string|TemplateObject $param The required parameter.
     * @param DirectView|null $entry The entry / context view.
     * @return string
     */
    private static function findValueInView(Protocol $protocol, $param, $entry = null) {
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
     * @return string
     */
    private static function serializeValue(Protocol $protocol, $param, $value, $entry, array $parameters) {
        if ($param instanceof TemplateObject) {
            $isView = $param->isView();
            $param = $param->getName();
        } else $isView = false;
        if (is_array($value)) {
            return $param . ': {' . implode(',', array_map(function ($key) use ($protocol, $value, $entry) {
                    $field = $value[$key];
                    if (!$protocol->innerView()) $innerView = $entry;
                    else $innerView = $protocol->innerView();
                    if ($innerView) $info = $innerView->get($field);
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
                        return $field . ': ' . $selector;
                    } else {
                        if (is_int($key)) $key = 'i' . $key;
                        if (is_string($field)) $field = '"' . $field . '"';
                        if (is_bool($field)) $field = ($field ? 'true' : 'false');
                        return $key . ': ' . $field;
                    }
                }, array_keys($value))) . '}';
        } else if ($value instanceof Context) {
            return $param . ': ' . $value->generate();
        } else if ($value instanceof View) {
            return $param . ': "' . $value->idc() . '"';
        } else if ($value instanceof TemplateObject) {
            return $param . ': con["' . $value->getName() . '"]';
        } else if ($value instanceof Protocol) {
            $reducedParameters = array_reduce(array_keys($parameters), function($pm, $p) use ($param, $parameters) {
                if ($p !== $param) $pm[$p] = $parameters[$p];
                return $pm;
            }, array());
            if (!$protocol->innerView()) $innerView = $entry;
            else $innerView = $protocol->innerView();
            return $param . ': () => {' . $value->generate($reducedParameters, $innerView) . '}';
        } else if (is_object($value)) {
            $key = $value->getKey();
            // Convert it
            $key = (is_integer($key) ? ActionRegister::convertKey($key) : $key);
            return $param . ': ' . (is_string($key) ? '"' . $key . '"' : $key);
        } else if ($value === null) {
            return $param . ': null';
        } else if (is_string($value)) {
            if (!$isView) return $param . ': ' . '"' . $value . '"';
            else {
                if (!$protocol->innerView()) $innerView = $entry;
                else $innerView = $protocol->innerView();
                $info = $innerView->get($value, true);
                if ($info) return $param . ': "' . $info["view"]->idc() . '"';
                else return '';
            }
        } else {
            return $param . ': ' . (is_bool($value) ? ($value ? 'true' : 'false') : $value);
        }
    }
}
?>