<?php

include_once dirname(__FILE__) . '/../../../ship/php/ActionProtocol.php';

/**
 * Class HtmlActionProtocol
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlActionProtocol extends ActionProtocol {

    /**
     * The url to the server
     * @var bool|string
     */
    public static $serverURL = false;

    /**
     * @inheritDoc
     */
    public function __construct(ModifyProtocol $parent, $controller, $method, $parameters = array(), $title = array()) {
        parent::__construct($parent, $controller, $method, "HtmlModifyProtocol", $parameters, $title);
        if (!HtmlActionProtocol::$serverURL) {
            $ini = parse_ini_file(dirname(__FILE__) . "/../../../cfg.ini", TRUE);
            $urls = $ini["urls"];

            HtmlActionProtocol::$serverURL = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ?
                    "https://" : "http://") . $_SERVER['SERVER_NAME'] . '/' . $urls['baseUrl'];
        }
    }

    /**
     * @inheritDoc
     */
    public function generate() {
        $reflection = new ReflectionClass($this->controller);
        $reflection = $reflection->getMethod($this->method);
        $parameters = $reflection->getParameters();
        $data = array_map(function(ReflectionParameter $param) {
            if (!array_key_exists($param->getName(), $this->parameters)) {
                $info = $this->innerView()->get($param->getName());
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
                return $param->getName() . ': ' . $selector;
            } else {
                $parameter = $this->parameters[$param->getName()];
                if (is_array($parameter)) {
                    return $param->getName() . ': {' . implode(',', array_map(function ($key) use ($parameter) {
                            $field = $parameter[$key];
                            $info = $this->innerView()->get($field);
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
                        }, array_keys($parameter))) . '}';
                } else if ($parameter instanceof Context) {
                    return $param->getName() . ': ' . $parameter->generate();
                } else if (is_object($parameter)) {
                    $key = $parameter->getKey();
                    return $param->getName() . ': ' . (is_string($key) ? '"' . $key . '"' : $key);
                } else if ($parameter === null) {
                    return $param->getName() . ': null';
                } else {
                    return $param->getName() . ': ' . (is_string($parameter) ?
                            '"' . $parameter . '"' : (is_bool($parameter) ? ($parameter ? 'true' : 'false') : $parameter));
                }
            }
        }, $parameters);

        $isGet = (preg_match('/@get(.*)/', $reflection->getDocComment(), $matches) >= 1);
        $without = (preg_match('/@without(.*)/', $reflection->getDocComment(), $matches) >= 1);
        $isCSV = (preg_match('/@CSV(.*)/', $reflection->getDocComment(), $matches) >= 1);
        $code = 'let data = { ' . implode(',', $data) . ' };/*console.log(data);*/' .
            'let taskId = context.currentTaskId++;' .
            '$(".system-tasks").trigger("' . Event::NEW_TASK . '", ["' . $this->title[View::getLanguage()] .'", taskId]);' .
            '$.ajax({' .
            'type: ' . ($isGet ? '"GET",' : '"POST",') .
            'url: "' . HtmlActionProtocol::$serverURL . '/sunstone/api/index.php/v1/"' .
                (!$without ? ' + context["user"] + "/" + context["group"] + "/"' : '') . ' + "api/' .
            $this->method . '",' .
            (!$isCSV ? 'contentType: "application/json; charset=utf-8",' : 'contentType: "text/csv; charset=utf-8",') .
            'data: JSON.stringify(data),' .
            'encoding: "UTF-8",' .
            'xhrFields: { withCredentials:true },' .
            'cache: false' .
            '})';

        $code .= implode(array_map(function(array $p) use ($isCSV) {
            if ($p["operation"] === "success") {
                return '.done(function(result) {(function(result) {' . $p["protocol"]->generate() . '})(result);' .
                    ($isCSV ? 'let uri = "data:application/csv;charset=UTF-8," + encodeURIComponent(result);' .
                    'window.open(uri, "data.csv");' : '') .
                    '$(".system-tasks").trigger("' . Event::REMOVE_TASK . '", [taskId]);})';
            } else if ($p["operation"] === "error") {
                return '.fail(function(result) {(function(result) {' . $p["protocol"]->generate() . '})(result);' .
                    '$(".system-tasks").trigger("' . Event::REMOVE_TASK . '", [taskId]);})';
            } else {
                return '.always(function(result) {(function(result) {' . $p["protocol"]->generate() . '})(result);' .
                    '$(".system-tasks").trigger("' . Event::REMOVE_TASK . '", [taskId]);})';
            }
        }, $this->protocol)) . ';';

        return $code;
    }
}
?>