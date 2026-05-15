<?php

require_once __DIR__ . '/../../webship/php/model/menus/ControlPanelHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/buttons/ButtonHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/buttons/StandardButtonHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/cards/CardHtmlView.php';

use webship\cards\CardHtmlView;
use webship\core\ApplicationHtmlView;
use webship\core\HtmlView;
use webship\interoperability\WEvent;
use webship\menus\ControlPanelHtmlView;
use webship\menus\MenuHtmlView;
use webship\menus\SubMenuHtmlView;
use webship\symbols\ImageHtmlView;
use ship\views\TextView;
use webship\protocols\HtmlModifyProtocol;
use webship\protocols\HtmlEventProtocol;
use webship\protocols\HtmlActionProtocol;


class DummyController
{
    public function index() : ApplicationHtmlView
    {


        $app = ApplicationHtmlView::create('WebShip Demo');
        $app->js('context["server"] = "' . APP_BASE_PATH . '";');

        $app->js('context["api"] = () => "/api/";');

        $app->js('context["apiSafe"] = () => context["api"]();');

        $app->js("setContext('server', '" . APP_BASE_PATH . "/v1');");

        ApplicationHtmlView::addStyle(\webship\WebshipTools::css('.menu', [],'position: static !important'));

        $menu = MenuHtmlView::create();

        $menu->entry()->texts('Home');

        $products = new SubMenuHtmlView();
        $products->texts('SubMenu');
        $products->entry()->texts('Entry 1');
        $products->entry()->texts('Entry 2');
        $products->entry()->texts('Entry 3');
        $menu->add($products);

        $menu->entry()->texts('About');
        $menu->entry()->texts('Contact');

        $content = new HtmlView();
        $content->addStyle('padding:20px;');

        $title = new TextView();
        $title->text(0, "WebShip Demo Dashboard");
        $content->add($title);

        $icons = new HtmlView();
        $icons->addStyle('display:flex; gap:20px; margin:15px 0;');

        $icons->add((new ImageHtmlView('/webship/pic/help.svg'))->localStyle('width:40px'));
        $icons->add((new ImageHtmlView('/webship/pic/wand.svg'))->localStyle('width:40px'));
        $icons->add((new ImageHtmlView('/webship/pic/update-arrow.svg'))->localStyle('width:40px'));

        $content->add($icons);

        $t1 = new TextView();
        $t1->text(0, "Welcome to the open-sourced Webship. ");

        $t2 = new TextView();
        $t2->text(0, "Below are some test images using ImageHtmlView.");

        $content->add($t1);
        $content->add($t2);

        $row1 = new HtmlView();

        $img1 = (new ImageHtmlView('/webship/pic/help.svg'))->localStyle('width:40px');
        $img2 = (new ImageHtmlView('/webship/pic/down.svg'))->localStyle('width:40px');
        $img3 = ((new ImageHtmlView('/webship/pic/alert-info.svg'))->localStyle('width:40px'));

        $row1->add($img1);
        $row1->add($img2);
        $row1->add($img3);

        $content->add($row1);

        // htmlview which will be replaced upon clicking the loadDashboard button
        $data = new HtmlView();
        $data->ref('data-area');

        $defaultText = new TextView();
        $defaultText->text(0, "Click the button to load data.");
        $data->add($defaultText);

        $content->add($data);

        $text = new TextView();
        $text->text(0, "Click the button to load dashboard.");


        $panel = ControlPanelHtmlView::create(1,1);
        $btn = $panel->button('load', '/webship/pic/box.png');
        $btn->protocol(WEvent::CLICK)
            ->action(DummyController::class, 'loadDashboard', [], [
                0 => 'Loading dashboard...'
            ])
            ->success()
            ->replace('data-area')
            ->parent();

        $content->add($panel);


        $content->ref('content');

        $root = new HtmlView();
        $root->add($menu);
        $root->add($content);

        $app->add($root);

        return $app;
    }

    /**
     * @without
     */
    public function loadDashboard() : HtmlView
    {
        $container = new HtmlView();

        $card = CardHtmlView::create('Dashboard loaded!');
        $val1 = new TextView();
        $val1->text(0, "123,4567");

        $row = new HtmlView();

        $row->add((new ImageHtmlView('/webship/pic/alert-info.svg'))->localStyle('width:40px'));
        $row->add((new ImageHtmlView('/webship/pic/wand.svg'))->localStyle('width:40px'));
        $card->add($row);

        $card->add($val1);
        $container->add($card);
        return $container;
    }
}