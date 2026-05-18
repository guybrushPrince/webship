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


class DummyController {
    
    /**
     * @get
     */
    public function app() : ApplicationHtmlView {
        $app = ApplicationHtmlView::create('WebShip Demo');
        $app->js('context["server"] = "' . APP_BASE_PATH . '";');
        $app->js('context["api"] = () => "/api/";');
        $app->js('context["apiSafe"] = () => context["api"]();');
        $app->js("setContext('server', '" . APP_BASE_PATH . "/v1');");

        // Example navigation bar/menu
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

        // Title and description
        $titleView = $content->view();
        $titleView->texts('WebShip Demo');

        $descView = $content->view();
        $descView->texts("This is an example page which demonstrates WebShip's capabilities.");

        // ImagevHtmlView example
        $imageExample = new HtmlView();        
        $imgTitle = new TextView();
        $imgTitle->text(0, "ImageHtmlView Example");
        $imageExample->add($imgTitle);

        $imgView = new HtmlView();
        $img = new ImageHtmlView('/webship/pic/wand.svg');
        $img->localStyle('width: 80px; border: 1px solid; padding: 4px; margin-top: 10px;');
        $imgView->add($img);
        $imageExample->add($imgView);

        $content->add($imageExample);

        $actionDesc = new TextView();
        $actionDesc->text(0, "Clicking the button below will send a value to the server. The server will return a View, that replaces the content in the 'data-area' below.");
        $content->add($actionDesc);

        // Data Area
        $dataArea = new HtmlView();
        $dataArea->ref('data-area');

        $defaultText = new TextView();
        $defaultText->text(0, "Waiting for action...");
        $dataArea->add($defaultText);
        $content->add($dataArea);

        // Control Panel and Button
        $panel = ControlPanelHtmlView::create(1,1);
        $btn = $panel->button('load', '/webship/pic/box.png');
        $btn->protocol(WEvent::CLICK)
            ->action(DummyController::class, 'processData', ['clientMessage' => 'Hello from Client!'], [
                0 => 'Transmitting...'
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
    public function processData(string $clientMessage = "No data received") : HtmlView {
        $container = new HtmlView();

        $card = CardHtmlView::create('Server Response Received!');
        $val1 = new TextView();
        $val1->text(0, "Received message: " . $clientMessage);

        $row = new HtmlView();
        $row->add((new ImageHtmlView('/webship/pic/alert-info.svg'))->localStyle('width:40px'));
        $row->add((new ImageHtmlView('/webship/pic/wand.svg'))->localStyle('width:40px'));
        $card->add($row);

        $card->add($val1);
        $container->add($card);
        
        return $container;
    }
}