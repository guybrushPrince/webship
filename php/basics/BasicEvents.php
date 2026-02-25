<?php

namespace webship\controller;

use webship\interoperability\WEvent;
use ship\controllers\EventAdder;
use ship\interoperability\SLanguage as Language;
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

    //
    // Templates
    //
    public const AddClass = 'AddClass';
    public const RemoveClass = 'RemoveClass';
    public const RemoveClassesFromSiblings = 'RemoveClassesFromSiblings';
    public const ToggleClass = 'ToggleClass';
    public const Reset = 'Reset';
    public const ThrowEvent = 'ThrowEvent';
    public const ThrowEventToObjectBinding = 'ThrowEventToObjectBinding';
    public const ThrowEventToObject = 'ThrowEventToObject';
    public const UnbindEventFromObject = 'UnbindEventFromObject';
    public const AddView = 'AddView';
    public const Delete = 'Delete';
    public const SetValue = 'SetValue';
    public const OpenHelp = 'OpenHelp';

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
        HtmlTemplateProtocol::_new(self::AddClass, self::ENVIRONMENT)->
            addClass(TemplateObject::_new('view'), TemplateObject::_new('class'));

        HtmlTemplateProtocol::_new(self::RemoveClass, self::ENVIRONMENT)->
            removeClass(TemplateObject::_new('view'), TemplateObject::_new('class'));

        HtmlTemplateProtocol::_new(self::RemoveClassesFromSiblings, self::ENVIRONMENT)->
            removeClassSiblings(TemplateObject::_new('view'), TemplateObject::_new('class'),
                TemplateObject::_new('onlyChildren'));

        HtmlTemplateProtocol::_new(self::ToggleClass, self::ENVIRONMENT)->
            toggleClass(TemplateObject::_new('view'), TemplateObject::_new('class'));

        HtmlTemplateProtocol::_new(self::Reset, self::ENVIRONMENT)->
            reset(TemplateObject::_new('view'));

        HtmlTemplateProtocol::_new(self::ThrowEvent, self::ENVIRONMENT)->
            throwevent(TemplateObject::_new('event'), TemplateObject::_new('view'), TemplateObject::_new('data'));

        HtmlTemplateProtocol::_new(self::ThrowEventToObjectBinding, self::ENVIRONMENT)->
            throwevent(WEvent::OBJECTMODIFY, TemplateObject::_new('object'), TemplateObject::_new('data'));

        HtmlTemplateProtocol::_new(self::ThrowEventToObject, self::ENVIRONMENT)->
            throwObjectEvent(TemplateObject::_new('event'), TemplateObject::_new('object'), TemplateObject::_new('scope'), TemplateObject::_new('data'));

        HtmlTemplateProtocol::_new(self::UnbindEventFromObject, self::ENVIRONMENT)->
            unbindObjectEvent(TemplateObject::_new('view'), TemplateObject::_new('event'), TemplateObject::_new('object'), TemplateObject::_new('scope'));

        HtmlTemplateProtocol::_new(self::AddView, self::ENVIRONMENT)->
            add(TemplateObject::_new('view'), TemplateObject::_new('add'));

        HtmlTemplateProtocol::_new(self::Delete, self::ENVIRONMENT)->
            delete(TemplateObject::_new('view'));

        HtmlTemplateProtocol::_new(self::SetValue, self::ENVIRONMENT)->
            value(TemplateObject::_new('view'), TemplateObject::_new('value'));

        HtmlTemplateProtocol::_new(self::OpenHelp, self::ENVIRONMENT)->
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