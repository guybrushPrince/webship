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
    public function __construct(ModifyProtocol $parent, $controller, $method, $parameters = array(), $title = array(), $seaStar = false) {
        parent::__construct($parent, $controller, $method, "HtmlModifyProtocol", $parameters, $title, $seaStar);
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
    public function generate() : string {
        if ($this->seaStar !== SeaStarMethod::EVENT) {
            $reflection = new ReflectionClass($this->controller);
            $reflection = $reflection->getMethod($this->method);
            $parameters = $reflection->getParameters();
            $data = ParameterSerializer::serialize($this, $parameters, $this->parameters);
        }

        if (!$this->seaStar) { // AJAX Call
            $isGet = (preg_match('/@get(.*)/', $reflection->getDocComment(), $matches) >= 1);
            $without = (preg_match('/@without(.*)/', $reflection->getDocComment(), $matches) >= 1);
            $isCSV = (preg_match('/@CSV(.*)/', $reflection->getDocComment(), $matches) >= 1);
            $name = null;
            if (preg_match('/@CSV(.*)/', $reflection->getDocComment(), $matches) >= 1) {
                $name = trim($matches[1], " ,:" . PHP_EOL);
                if ($name === "") $name = null;
            }
            $isPDF = (preg_match('/@PDF(.*)/', $reflection->getDocComment(), $matches) >= 1);
            if (preg_match('/@PDF(.*)/', $reflection->getDocComment(), $matches) >= 1) {
                $name = trim($matches[1], " ,:" . PHP_EOL);
                if ($name === "") $name = null;
            }
            $isJS = (preg_match('/@JS(.*)/', $reflection->getDocComment(), $matches) >= 1);

            $code = 'let taskId = nId();';
            $code .= 'request({ ' . implode(',', $data) . ' },';
            $code .= ($isGet ? '"GET",' : '"POST",');
            $code .= 'context["api' . (!$without ? 'Safe' : '') . '"](),';
            $code .= '"' . $this->method . '",';
            $code .= ($isCSV ? 'true' : 'false') . ',';
            $code .= ($isPDF ? 'true' : 'false') . ',';
            $code .= 'sysTask,';
            $code .= '{taskId: taskId, title: "' . $this->title[View::getLanguage()] . '"})';

            $code .= implode(array_map(function (array $p) use ($isCSV, $isPDF, $isJS, $name) {
                    if ($p["operation"] === "success") {
                        $func = '.done(function(result) {';
                        if (!$isJS) $func .= '(function(result) {' . $p["protocol"]->generate() . '})(result);';
                        else $func .= '(function(result) {addScript(result);result="";' . $p["protocol"]->generate() . '})(result);';
                        if ($isCSV || $isPDF) {
                            $d = '(new Date()).toISOString().split(/[-T:,.]/).slice(0,6).join("_")';
                            $func .= 'downloadFile(result, ' . ($isPDF ? '"' . ($name ? $name : 'download') . '_" + ' . $d . ' + "' . '.pdf"' :
                                    '"' . ($name ? $name : 'data') . '_" + ' . $d . ' + "' . '.csv"') . ');';
                        }
                        $func .= 'sysTF(taskId);})';
                        return $func;
                    } else if ($p["operation"] === "error") {
                        $func = '.fail(function(result) {';
                        $func .= 'result = result.responseText;';
                        if (!$isJS) $func .= '(function(result) {' . $p["protocol"]->generate() . '})(result);';
                        else $func .= '(function(result) {addScript(result);result="";' . $p["protocol"]->generate() . '})(result);';
                        if ($isCSV || $isPDF)
                            $func .= 'downloadFile(result, ' . ($isPDF ? '"download.pdf"' : '"data.csv"') . ');';
                        $func .= 'sysTF(taskId);})';
                        return $func;
                    } else {
                        return '.always(function(result) {(function(result) {' . $p["protocol"]->generate() . '})(result);' .
                            'sysTF(taskId);})';
                    }
                }, $this->protocol)) . ';';
        } else { // WebSocket Call
            $code = 'seastar.';
            // Add the method
            switch ($this->seaStar) {
                case SeaStarMethod::CALL: { $code .= 'call'; break; }
                case SeaStarMethod::START: { $code .= 'atStart'; break; }
                case SeaStarMethod::EVENT: { $code .= 'regListener'; break; }
                default: { $code .= ''; break; }
            }
            // Add controller, method and context, event respectively.
            $code .= '("' . $this->controller . '","' . $this->method . '"' . ',';
            if ($this->seaStar === SeaStarMethod::CALL || $this->seaStar === SeaStarMethod::START) {
                $code .= '{' . implode(',', $data) . '},';
            }
            // Add callbacks.
            $code .= implode(',', array_map(function (array $p) {
                    return 'function(result) {' . $p["protocol"]->generate() . '}';
                }, $this->protocol));
            $code .= ');';
        }

        return $code;
    }
}
?>