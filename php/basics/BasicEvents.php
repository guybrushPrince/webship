<?php
/**
 * Class BasicEvents
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
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

        HtmlTemplateProtocol::_new('AddView', 'basics')->
            add(TemplateObject::_new('view'), TemplateObject::_new('add'));

        HtmlTemplateProtocol::_new('Delete', 'basics')->
            delete(TemplateObject::_new('view'));

        HtmlTemplateProtocol::_new('SetValue', 'basics')->
            value(TemplateObject::_new('view'), TemplateObject::_new('value'));

        return true;
    }
}
?>