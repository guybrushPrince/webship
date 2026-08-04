<?php

namespace webship\protocols;

use ReflectionClass;
use ReflectionException;
use ship\protocols\ActionProtocol;
use ship\protocols\ModifyProtocol;
use ship\views\View;
use webship\interoperability\IWebSocketAction;
use webship\interoperability\ParameterSerializer;
use webship\WebshipTools;

/**
 * Class HtmlActionProtocol.
 *
 * <p>WebShip implementation of {@link ActionProtocol} specified in Ship.</p>
 *
 * @see ActionProtocol
 *
 * @package webship
 * @subpackge php/controller
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class HtmlActionProtocol extends ActionProtocol {

    /**
     * Registered socket actions.
     * @var IWebSocketAction[]
     */
    private static array $sockets = [];


    /**
     * @inheritDoc
     */
    public function __construct(ModifyProtocol $parent, string $controller, string $method,
                                array $parameters = [], array $title = [], string|int|null $seaStar = null) {
        parent::__construct($parent, $controller, $method, HtmlModifyProtocol::class,
            $parameters, $title, $seaStar);
    }

    /**
     * Register a socket action of type {@link IWebSocketAction}.
     * @param IWebSocketAction $socketAction The socket action.
     * @return void
     */
    public static function registerSocketAction(IWebSocketAction $socketAction) : void {
        self::$sockets[] = $socketAction;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function generate() : string {
        if (!is_null($this->action) && !array_key_exists($this->action, self::$prohibitedActions)) {
            $reflection = new ReflectionClass($this->controller);
            $reflection = $reflection->getMethod($this->method);
            $parameters = $reflection->getParameters();
            $data = ParameterSerializer::serialize($this, $parameters, $this->parameters);
        }

        if (is_null($this->action) || !array_key_exists($this->action, self::$prohibitedActions)) { // AJAX Call
            $isGet = (preg_match('/@get(.*)/', $reflection->getDocComment(), $matches) >= 1);
            $without = (preg_match('/@without(.*)/', $reflection->getDocComment(), $matches) >= 1);
            $isCSV = (preg_match('/@CSV(.*)/', $reflection->getDocComment(), $matches) >= 1);
            $isJSON = (preg_match('/@JSON(.*)/', $reflection->getDocComment(), $matches) >= 1);
            $name = null;
            if (preg_match('/@CSV(.*)/', $reflection->getDocComment(), $matches) >= 1) {
                $name = trim($matches[1], " ,:\n\r" . PHP_EOL);
                if ($name === "") $name = null;
            }
            $isPDF = (preg_match('/@PDF(.*)/', $reflection->getDocComment(), $matches) >= 1);
            if ($isPDF) {
                $name = trim($matches[1], " ,:\n\r" . PHP_EOL);
                if ($name === "") $name = null;
            }
            $isZIP = (preg_match('/@ZIP(.*)/', $reflection->getDocComment(), $matches) >= 1);
            if ($isZIP) {
                $name = trim($matches[1], " ,:\n\r" . PHP_EOL);
                if ($name === "") $name = null;
            }
            $isJS = (preg_match('/@ECMA(.*)/', $reflection->getDocComment(), $matches) >= 1);

            $code = 'let taskId = nId();';
            $code .= 'request({ ' . implode(',', $data) . ' },';
            $code .= ($isGet ? '"GET",' : '"POST",');
            $code .= 'context["api' . (!$without ? 'Safe' : '') . '"](),';
            $code .= '"' . $this->method . '",';
            $code .= ($isCSV ? 'true' : 'false') . ',';
            $code .= ($isPDF ? 'true' : 'false') . ',';
            $code .= ($isZIP ? 'true' : 'false') . ',';
            $code .= 'sysTask,';
            $code .= '{taskId: taskId, title: "' . $this->title[View::getLanguage()] . '"})';

            $code .= implode(array_map(function (array $p) use ($isCSV, $isPDF, $isZIP, $isJS, $isJSON, $name) {
                    if ($p["operation"] === "success" || $p["operation"] === "fail") {
                        $func = '.done(function(result) {';
                        $hasFail = !WebshipTools::isEmpty(
                            array_filter($this->protocol, function (array $pr) {
                                return $pr["operation"] === "fail";
                            })
                        );
                        $taskState = 'SUCCESS';
                        if ($hasFail && $p["operation"] === "success") {
                            $func .= 'if (isObject(result) && ("__intern_fail" in result)) return;';
                        }
                        if ($hasFail && $p["operation"] === "fail") {
                            $func .= 'if (!(isObject(result) && "__intern_fail" in result)) return;';
                            $func .= 'result = result["content"];';
                            $taskState = 'FAIL';
                        }
                        if ($isJS && $p["operation"] === "success") {
                            $func .= 'eval(result);';
                            //$func .= '(function(result) {' . $p["protocol"]->generate() . '})("");';
                            $func .= '((result) => {' . $p["protocol"]->generate() . '})("");';
                        } else {
                            if (!$isJSON) $func .= 'result = extractStyle(result);';
                            if (!$isJS || $hasFail && $p["operation"] === "fail") $func .= '(function(result) {' . $p["protocol"]->generate() . '})(result);';
                            else $func .= '(function(result) {' . ($hasFail ? 'result="";' : '') . $p["protocol"]->generate() . '})(result);';
                            if (($isCSV || $isPDF || $isZIP) && $p["operation"] === "success") {
                                $d = '(new Date()).toISOString().split(/[-T:,.]/).slice(0,3).join("_")';
                                $func .= 'downloadFile(result, ' . ($isPDF || $isZIP ? '"' . ($name ? $name : 'download') . '_" + ' . $d . ' + "' . ($isPDF ? '.pdf"' : '.zip"') :
                                        '"' . ($name ? $name : 'data') . '_" + ' . $d . ' + "' . '.csv"') . ',' .
                                    ($isCSV ? 'false' : 'true') . ');';
                            }
                        }
                        $func .= 'sysTF(taskId, TaskState.' . $taskState . ');})';
                        return $func;
                    } else if ($p["operation"] === "error") {
                        $func = '.fail(function(result) {';
                        $func .= 'result = result.responseText;';
                        $func .= 'result = extractStyle(result);';
                        if (!$isJS) $func .= '(function(result) {' . $p["protocol"]->generate() . '})(result);';
                        else $func .= '(function(result) {' . 'result="";' . $p["protocol"]->generate() . '})(result);';
                        $func .= 'sysTF(taskId, TaskState.ERROR);})';
                        return $func;
                    } else {
                        return '.always(function(result) {result = extractStyle(result);(function(result) {' . $p["protocol"]->generate() . '})(result);' .
                            'sysTF(taskId, TaskState.FINALLY);})';
                    }
                }, $this->protocol)) . ';';
            return $code;
        } else { // WebSocket Call
            foreach (self::$sockets as $socketAction) {
                $code = $socketAction->action($this->controller, $this->method, $this->action,
                    $data ?? null, $this->protocol);
                if (!is_null($code)) return $code;
            }
        }

        return '';
    }
}
?>