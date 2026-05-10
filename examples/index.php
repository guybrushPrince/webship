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

$app->setBasePath('/webship/examples');


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