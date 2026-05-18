<?php

require_once __DIR__ . '../../../ship/php/protocols/TemplateProtocol.php';
require_once __DIR__ . '../../../ship/php/controller/EventAdder.php';
require_once __DIR__ . '../../php\protocols\HtmlTemplateProtocol.php';
require_once __DIR__ . '../../../ship/php/template/TemplateObject.php';
require_once __DIR__ . '../../php/interoperability/ParameterSerializer.php';
use ship\controllers\EventAdder;
use webship\protocols\HtmlTemplateProtocol;
use ship\template\TemplateObject;

class DemoEvents implements EventAdder
{

    public static function environment(): string
    {
        return 'demo';
    }

    public static function add(): bool
    {
        HtmlTemplateProtocol::_new('ProcessData', 'demo')
            ->action(DummyController::class, 'processData')
            ->success()
            ->replace(TemplateObject::_new('data-area'))
            ->parent()
            ->error()
            ->parent();

        return true;
    }
}