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
        $data = ParameterSerializer::serialize($this, $parameters, $this->parameters);

        $isGet = (preg_match('/@get(.*)/', $reflection->getDocComment(), $matches) >= 1);
        $without = (preg_match('/@without(.*)/', $reflection->getDocComment(), $matches) >= 1);
        $isCSV = (preg_match('/@CSV(.*)/', $reflection->getDocComment(), $matches) >= 1);
        $isPDF = (preg_match('/@PDF(.*)/', $reflection->getDocComment(), $matches) >= 1);

        $code  = 'let taskId = nId();';
        $code .= 'request({ ' . implode(',', $data) . ' },';
        $code .= ($isGet ? '"GET",' : '"POST",');
        $code .= 'context["api' . (!$without ? 'Safe' : '') . '"](),';
        $code .= '"' . $this->method . '",';
        $code .= ($isCSV ? 'true' : 'false') . ',';
        $code .= ($isPDF ? 'true' : 'false') . ',';
        $code .= 'sysTask,';
        $code .= '{taskId: taskId, title: "' . $this->title[View::getLanguage()] . '"})';

        $code .= implode(array_map(function(array $p) use ($isCSV, $isPDF) {
            if ($p["operation"] === "success") {
                return '.done(function(result) {(function(result) {' . $p["protocol"]->generate() . '})(result);' .
                    ($isCSV || $isPDF ? 'downloadFile(result, ' . ($isPDF ? '"download.pdf"' : '"data.csv"') . ');' : '') .
                    'sysTF(taskId);})';
            } else if ($p["operation"] === "error") {
                return '.fail(function(result) {(function(result) {' . $p["protocol"]->generate() . '})(result);' .
                    'sysTF(taskId);})';
            } else {
                return '.always(function(result) {(function(result) {' . $p["protocol"]->generate() . '})(result);' .
                    'sysTF(taskId);})';
            }
        }, $this->protocol)) . ';';

        return $code;
    }
}
?>