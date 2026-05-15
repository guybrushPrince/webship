<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/composer/autoload_real.php';
require_once __DIR__ . '/../../ship/php/interoperability\SEnumeration.php';
require_once __DIR__ . '/../../ship/php/interoperability\SLanguage.php';
require_once __DIR__ . '/../../ship/php/views/View.php';
require_once __DIR__ . '/../../ship/php/views/TextView.php';
require_once __DIR__ . '/../../ship/php/interoperability/ShippingTools.php';
require_once __DIR__ . '/../../ship/php/protocols/Protocol.php';
require_once __DIR__ . '/../../ship/php/protocols/ActionProtocol.php';
require_once __DIR__ . '/../../ship/php/protocols/EventProtocol.php';
require_once __DIR__ . '/../../webship/php/util/WebshipTools.php';
require_once __DIR__ . '/../../webship\php\model\core\HtmlView.php';
require_once __DIR__ . '/../../webship/php/interoperability\WEvent.php';
require_once __DIR__ . '/../../webship/php/model/symbols/ImageHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/help/HintHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/dialogs/DialogHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/menus/MenuHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/menus/MenuEntryHtmlView.php';
require_once __DIR__ . '/../../webship/php/protocols/HtmlActionProtocol.php';
require_once __DIR__ . '/../../webship/php/model/menus/SubMenuHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/core/ApplicationHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/symbols/ImageHtmlView.php';
require_once __DIR__ . '/../../webship/php/protocols/HtmlEventProtocol.php';
require_once __DIR__ . '/../../webship/php/protocols/HtmlModifyProtocol.php';




use ship\views\TextView;
use ship\protocols\EventProtocol;
use ship\protocols\ActionProtocol;
use ship\controllers\ActionRegister;
use webship\core\ApplicationHtmlView;
use webship\core\HtmlView;
use webship\dialogs\DialogHtmlView;
use webship\help\HintHtmlView;
use webship\menus\MenuHtmlView;
use webship\menus\SubMenuHtmlView;
use webship\symbols\ImageHtmlView;
use webship\protocols\HtmlEventProtocol;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/DummyController.php';
require_once 'DemoEvents.php';

$app = AppFactory::create();

$basePath = (function () {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $uri = (string) parse_url('http://a' . ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    if (stripos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
        return $_SERVER['SCRIPT_NAME'];
    }
    if ($scriptDir !== '/' && stripos($uri, $scriptDir) === 0) {
        return $scriptDir;
    }
    return '';
})();

$app->setBasePath($basePath);

$ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443);
$host = $_SERVER['SERVER_NAME'];
$projectRoot = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'], 3));
$serverUrl = ($ssl ? "https://" : "http://") . $host . $projectRoot;
\webship\protocols\HtmlActionProtocol::setServerURL($serverUrl);

define('APP_BASE_PATH', $basePath);
define('PROJECT_ROOT_URL', $serverUrl);



EventProtocol::registerImplementation(HtmlEventProtocol::class, HtmlView::class);
$app->group("/v1", function (RouteCollectorProxy $group) {
    $ar = ActionRegister::instance($group);
    $ar->parseNotCompletely([ApplicationHtmlView::class]);
    $ar->register([
        DummyController::class
    ]);

    $ar->bindEvents([DemoEvents::class  ]);
    $group->get('/', function ($request, $response) {
        $controller = new DummyController();
        $view = $controller->index();

        $response->getBody()->write($view->generate());
        return $response;
    });
});

$app->get('/', function ($request, $response)
{
    $controller = new DummyController();
    $view = $controller->index();

    $response->getBody()->write($view->generate());
    return $response;
});

$app->addRoutingMiddleware();

$app->run();

?>