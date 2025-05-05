<?php
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
    /**
     * @inheritDoc
     */
    public static function add(): bool {
        HtmlTemplateProtocol::_new('AddClass', 'basics')->
            addClass(TemplateObject::_new('view'), TemplateObject::_new('class'));

        HtmlTemplateProtocol::_new('RemoveClass', 'basics')->
            removeClass(TemplateObject::_new('view'), TemplateObject::_new('class'));

        HtmlTemplateProtocol::_new('RemoveClassesFromSiblings', 'basics')->
            removeClassSiblings(TemplateObject::_new('view'), TemplateObject::_new('class'),
                TemplateObject::_new('onlyChildren'));

        HtmlTemplateProtocol::_new('ToggleClass', 'basics')->
            toggleClass(TemplateObject::_new('view'), TemplateObject::_new('class'));

        HtmlTemplateProtocol::_new('Reset', 'basics')->
            reset(TemplateObject::_new('view'));

        HtmlTemplateProtocol::_new('ThrowEvent', 'basics')->
            throwevent(TemplateObject::_new('event'), TemplateObject::_new('view'), TemplateObject::_new('data'));

        HtmlTemplateProtocol::_new('ThrowEventToObjectBinding', 'basics')->
            throwevent(Event::OBJECTMODIFY, TemplateObject::_new('object'), TemplateObject::_new('data'));

        HtmlTemplateProtocol::_new('ThrowEventToObject', 'basics')->
            throwObjectEvent(TemplateObject::_new('event'), TemplateObject::_new('object'), TemplateObject::_new('scope'), TemplateObject::_new('data'));

        HtmlTemplateProtocol::_new('UnbindEventFromObject', 'basics')->
            unbindObjectEvent(TemplateObject::_new('view'), TemplateObject::_new('event'), TemplateObject::_new('object'), TemplateObject::_new('scope'));

        HtmlTemplateProtocol::_new('AddView', 'basics')->
            add(TemplateObject::_new('view'), TemplateObject::_new('add'));

        HtmlTemplateProtocol::_new('Delete', 'basics')->
            delete(TemplateObject::_new('view'));

        HtmlTemplateProtocol::_new('SetValue', 'basics')->
            value(TemplateObject::_new('view'), TemplateObject::_new('value'));

        HtmlTemplateProtocol::_new('OpenHelp', 'basics')->
            action(BasicsController::class, 'openHelp', [
                    'package' => TemplateObject::_new('package'),
                    'subpackage' => TemplateObject::_new('subpackage'),
                    'doc' => TemplateObject::_new('doc'),
                    'language' => TemplateObject::_new('language')
                ], [
                    Language::ENGLISH => 'Open help ...',
                    Language::GERMAN =>  'Öffne Hilfe ...'
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