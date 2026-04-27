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


//use webship\alerts\AlertHtmlView;
//use webship\controller\BasicEvents;
//use webship\controller\BasicsController;
//use webship\controller\DashboardController;
//use webship\controller\DashboardFunctions;
//use webship\dashboards\DashboardHtmlView;
//use webship\protocols\HtmlEventProtocol;
//use webship\protocols\HtmlModifyProtocol;
//
// ROOT
//
//$root = new HtmlView();
//
////
////
////
//$menu = MenuHtmlView::create();
//
//$menu->entry()->texts('Main Menu');
//
//$products = new SubMenuHtmlView();
//$products->texts('Submenu1');
//$products->entry()->texts('entry1');
//$products->entry()->texts('entry2');
//$products->entry()->texts('entry3');
//$menu->add($products);
//
//$about = new SubMenuHtmlView();
//$about->texts('Submenu2');
//$about->entry()->texts('entry21');
//$about->entry()->texts('entry22');
//$about->entry()->texts('entry23');
//$menu->add($about);
//
//$menu->entry()->texts('Help');
//
//$root->add($menu);
//
////
//// ////
//$content = new HtmlView();
//
////
//// TEXT BLOCK
////
//$t1 = new TextView();
//$t1->text(0, "Welcome to the open-sourced Webship");
//
//$t2 = new TextView();
//$t2->text(0, "Below are some test images using ImageHtmlView.");
//
//$content->add($t1);
//$content->add($t2);
//
//
////
//$row1 = new HtmlView();
//
//$img1 = new ImageHtmlView('/webship/pic/help.svg');
//$img2 = new ImageHtmlView('/webship/pic/settings.svg');
//$img3 = new ImageHtmlView('/webship/pic/alert-info.svg');
//
//$row1->add($img1);
//$row1->add($img2);
//$row1->add($img3);
//
//$content->add($row1);
//
////
//$row2 = new HtmlView();
//
//$row2->add(new ImageHtmlView('/webship/pic/save.svg'));
//$row2->add(new ImageHtmlView('/webship/pic/down.svg'));
//$row2->add(new ImageHtmlView('/webship/pic/loupe.svg'));
//
//$content->add($row2);
//
//}
//
////
//$root->add($content);
//
////
//echo $root->generate();



$app = AppFactory::create();

$app->setBasePath('/webship/examples');

EventProtocol::registerImplementation(HtmlEventProtocol::class, HtmlView::class);
$app->group("/v1", function (RouteCollectorProxy $group) {
    $ar = ActionRegister::instance($group);

    $ar->register([
        DummyController::class
    ]);

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