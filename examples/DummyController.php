<?php

require_once __DIR__ . '/../../webship/php/model/menus/ControlPanelHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/buttons/ButtonHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/buttons/StandardButtonHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/cards/CardHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/titles/TitleHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/containers/StandardHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/alerts/AlertHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/alerts/InfoHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/inputs/InputHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/inputs/LabelledInputHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/inputs/SwitchHtmlView.php';
require_once __DIR__ . '/../../webship/php/model/charts/ProgressStatusHtmlView.php';

use webship\cards\CardHtmlView;
use webship\containers\StandardHtmlView;
use webship\core\ApplicationHtmlView;
use webship\core\HtmlView;
use webship\interoperability\WEvent;
use webship\menus\ControlPanelHtmlView;
use webship\menus\MenuHtmlView;
use webship\menus\SubMenuHtmlView;
use webship\symbols\ImageHtmlView;
use webship\titles\TitleHtmlView;
use webship\alerts\InfoHtmlView;
use webship\inputs\LabelledInputHtmlView;
use webship\inputs\SwitchHtmlView;
use webship\charts\ProgressStatusHtmlView;
use webship\protocols\HtmlModifyProtocol;
use webship\protocols\HtmlEventProtocol;
use webship\protocols\HtmlActionProtocol;
use ship\interoperability\SLanguage;


class DummyController {
    
    /**
     * @get
     */
    public function app() : ApplicationHtmlView {
        HtmlView::setLanguage(SLanguage::ENGLISH);
        $app = ApplicationHtmlView::create([SLanguage::ENGLISH => 'WebShip Demo']);
        $app->js('context["server"] = "' . APP_BASE_PATH . '";');
        $app->js('context["api"] = () => "/api/";');
        $app->js('context["apiSafe"] = () => context["api"]();');
        $app->js("setContext('server', '" . APP_BASE_PATH . "/v1');");

        // Example navigation bar/menu
        ApplicationHtmlView::addStyle(\webship\WebshipTools::css('.menu', [],'position: static !important'));
        $menu = MenuHtmlView::create();
        $menu->entry()->texts([SLanguage::ENGLISH => 'Home']);
        
        $products = new SubMenuHtmlView();
        $products->texts([SLanguage::ENGLISH => 'SubMenu']);
        $products->entry()->texts([SLanguage::ENGLISH => 'Entry 1']);
        $products->entry()->texts([SLanguage::ENGLISH => 'Entry 2']);
        $products->entry()->texts([SLanguage::ENGLISH => 'Entry 3']);
        $menu->add($products);
        
        $menu->entry()->texts([SLanguage::ENGLISH => 'About']);
        $menu->entry()->texts([SLanguage::ENGLISH => 'Contact']);

        $content = StandardHtmlView::createStandard();

        // Title and description
        $title = TitleHtmlView::create([SLanguage::ENGLISH => 'WebShip Demo']);
        $content->add($title);

        $welcomeAlert = InfoHtmlView::create([SLanguage::ENGLISH => 'Welcome to the WebShip Example page! This page showcases various components built with WebShip.']);
        $content->add($welcomeAlert);

        // ImageHtmlView Card Example
        $imageCard = CardHtmlView::create([SLanguage::ENGLISH => 'ImageHtmlView Example']);
        $img = new ImageHtmlView('/webship/pic/wand.svg');
        $img->localStyle('width: 80px;');
        $imageCard->content()->add($img);
        $content->add($imageCard);

        // 3. Form & Controls Demo Card
        $formCard = CardHtmlView::create([SLanguage::ENGLISH => 'LabelledInputHtmlView & SwitchHtmlView']);
        $formContent = $formCard->content();
        $formContent->localStyle('padding: 15px; display: flex; flex-direction: column; gap: 15px;');

        // Text field input
        $nameField = LabelledInputHtmlView::createTextField([SLanguage::ENGLISH => 'Username'], 'username');
        $formContent->add($nameField);

        // Numeric input
        $ageField = LabelledInputHtmlView::createNumericField([SLanguage::ENGLISH => 'Age'], 'age', [], 1, 0, 150);
        $formContent->add($ageField);

        // Toggle Switch
        $switchLabel = new HtmlView();
        $switchLabel->texts([SLanguage::ENGLISH => 'Enable notifications: ']);
        $switchLabel->localStyle('display: flex; align-items: center; gap: 10px;');
        $switchLabel->add(SwitchHtmlView::create('notifications_toggle', true));
        $formContent->add($switchLabel);
        $content->add($formCard);

        // Progress Tracker Card Example
        $progressCard = CardHtmlView::create([SLanguage::ENGLISH => 'ProgressStatusHtmlView']);
        $progressContent = $progressCard->content();
        $progressContent->localStyle('padding: 15px;');
        $progressContent->texts([SLanguage::ENGLISH => 'Current Project Status:']);
        
        $progress = ProgressStatusHtmlView::create(
            ['Planning', 'Development', 'Testing', 'Release'],
            'Testing'
        );

        $progressContent->add($progress);
        $content->add($progressCard);

        // ActionProtocol Card Example
        $actionCard = CardHtmlView::create([SLanguage::ENGLISH => 'ControlPanelHtmlView & WEvent::CLICK']);
        $actionCardContent = $actionCard->content();
        $actionCardContent->localStyle('padding: 15px;');

        $actionCardContent->texts([SLanguage::ENGLISH => "Clicking the button below will send a value to the server. The server will return a View, that replaces the content in the 'data-area' below."]);

        // Data Area
        $dataArea = new HtmlView();
        $dataArea->ref('data-area');
        $dataArea->texts([SLanguage::ENGLISH => 'Waiting for action...']);
        $dataArea->localStyle('padding: 15px; border: 1px dashed var(--ws-card-title-brd); margin: 15px 0;');
        $actionCardContent->add($dataArea);

        // Control Panel and Button
        $panel = ControlPanelHtmlView::create(1, 1);
        $btn = $panel->button('load', '/webship/pic/box.png');
        $btn->protocol(WEvent::CLICK)
            ->action(DummyController::class, 'processData', ['clientMessage' => 'Hello from Client!'], [
                SLanguage::ENGLISH => 'Transmitting...'
            ])
            ->success()
            ->replace('data-area')
            ->parent();

        $actionCardContent->add($panel);
        $content->add($actionCard);

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
        HtmlView::setLanguage(SLanguage::ENGLISH);
        $container = new HtmlView();

        $card = CardHtmlView::create([SLanguage::ENGLISH => 'Server Response Received!']);
        $val1 = new HtmlView();

        $val1->texts([SLanguage::ENGLISH => 'Received message: ' . $clientMessage]);

        $row = new HtmlView();
        $row->add((new ImageHtmlView('/webship/pic/alert-info.svg'))->localStyle('width:40px'));
        $row->add((new ImageHtmlView('/webship/pic/wand.svg'))->localStyle('width:40px'));
        $card->add($row);

        $card->add($val1);
        $container->add($card);
        
        return $container;
    }
}