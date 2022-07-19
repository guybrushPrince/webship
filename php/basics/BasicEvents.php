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
            throwevent(TemplateObject::_new('event'), TemplateObject::_new('view'));

        HtmlTemplateProtocol::_new('Delete', 'basics')->
            delete(TemplateObject::_new('view'));

        return true;
    }
}
?>