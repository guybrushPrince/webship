<?php

namespace webship\controller;

use Event;
use Language;
use ship\controllers\EventAdder;
use ship\template\TemplateObject;
use webship\protocols\HtmlTemplateProtocol;

/**
 * Class BasicEvents.
 *
 * <p>Provides events / scripts being usually required for typical web applications.</p>
 *
 * @package webship
 * @subpackge php/basics
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class BasicEvents implements EventAdder {

    public const ENVIRONMENT = 'basics';

    /**
     * @inheritDoc
     */
    public static function environment(): string {
        return self::ENVIRONMENT;
    }
    
    /**
     * @inheritDoc
     */
    public static function add() : bool {
        HtmlTemplateProtocol::_new('AddClass', self::ENVIRONMENT)->
            addClass(TemplateObject::_new('view'), TemplateObject::_new('class'));

        HtmlTemplateProtocol::_new('RemoveClass', self::ENVIRONMENT)->
            removeClass(TemplateObject::_new('view'), TemplateObject::_new('class'));

        HtmlTemplateProtocol::_new('RemoveClassesFromSiblings', self::ENVIRONMENT)->
            removeClassSiblings(TemplateObject::_new('view'), TemplateObject::_new('class'),
                TemplateObject::_new('onlyChildren'));

        HtmlTemplateProtocol::_new('ToggleClass', self::ENVIRONMENT)->
            toggleClass(TemplateObject::_new('view'), TemplateObject::_new('class'));

        HtmlTemplateProtocol::_new('Reset', self::ENVIRONMENT)->
            reset(TemplateObject::_new('view'));

        HtmlTemplateProtocol::_new('ThrowEvent', self::ENVIRONMENT)->
            throwevent(TemplateObject::_new('event'), TemplateObject::_new('view'), TemplateObject::_new('data'));

        HtmlTemplateProtocol::_new('ThrowEventToObjectBinding', self::ENVIRONMENT)->
            throwevent(Event::OBJECTMODIFY, TemplateObject::_new('object'), TemplateObject::_new('data'));

        HtmlTemplateProtocol::_new('ThrowEventToObject', self::ENVIRONMENT)->
            throwObjectEvent(TemplateObject::_new('event'), TemplateObject::_new('object'), TemplateObject::_new('scope'), TemplateObject::_new('data'));

        HtmlTemplateProtocol::_new('UnbindEventFromObject', self::ENVIRONMENT)->
            unbindObjectEvent(TemplateObject::_new('view'), TemplateObject::_new('event'), TemplateObject::_new('object'), TemplateObject::_new('scope'));

        HtmlTemplateProtocol::_new('AddView', self::ENVIRONMENT)->
            add(TemplateObject::_new('view'), TemplateObject::_new('add'));

        HtmlTemplateProtocol::_new('Delete', self::ENVIRONMENT)->
            delete(TemplateObject::_new('view'));

        HtmlTemplateProtocol::_new('SetValue', self::ENVIRONMENT)->
            value(TemplateObject::_new('view'), TemplateObject::_new('value'));

        HtmlTemplateProtocol::_new('OpenHelp', self::ENVIRONMENT)->
            action(BasicsController::class, 'openHelp', [
                'package' => TemplateObject::_new('package'),
                'subpackage' => TemplateObject::_new('subpackage'),
                'doc' => TemplateObject::_new('doc'),
                'language' => TemplateObject::_new('language')
            ], [
                Language::ENGLISH => 'Open help ...',
                Language::GERMAN => 'Öffne Hilfe ...'
            ])->
            success()->
                replace(TemplateObject::_new('replace'))->
                add(TemplateObject::_new('add'))->
            parent()->
            error()->
                add(TemplateObject::_new('error'))->
            parent();

        return true;
    }
}
?>