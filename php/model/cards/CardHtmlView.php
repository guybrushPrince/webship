<?php

/**
 * Class CardHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class CardHtmlView extends HtmlView {

    /**
     * CardHtmlView constructor.
     * @param string[] $classes Some additional classes.
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge($classes, ['card']));

        $this->style(Tools::css('.card', [], 'position: relative', 'margin: .5rem 0 1rem 0',
            'background-color: #fff', '-webkit-transition: -webkit-box-shadow .25s',
            'transition: box-shadow .25s', 'border-radius: 2px',
            '-webkit-box-shadow: 0 2px 2px 0 rgb(0 0 0 / 14%), 0 3px 1px -2px rgb(0 0 0 / 12%), 0 1px 5px 0 rgb(0 0 0 / 20%)',
            'box-shadow: 0 2px 2px 0 rgb(0 0 0 / 14%), 0 3px 1px -2px rgb(0 0 0 / 12%), 0 1px 5px 0 rgb(0 0 0 / 20%)'));

        $this->style(Tools::css('.card', [':hover'],
            '-webkit-box-shadow: 0 2px 2px 0 rgb(0 0 0 / 28%), 0 3px 1px -2px rgb(0 0 0 / 24%), 0 1px 5px 0 rgb(0 0 0 / 40%)',
            'box-shadow: 0 2px 2px 0 rgb(0 0 0 / 28%), 0 3px 1px -2px rgb(0 0 0 / 24%), 0 1px 5px 0 rgb(0 0 0 / 40%)'));

        $this->style(Tools::css('.card', [' > ', '.title'], 'color: rgba(0, 0, 0, 1.0)',
            'width: 100%', 'max-width: 100%', 'padding: 24px', 'font-size: 24px',
            'font-weight: 300', 'box-sizing: border-box', 'background: rgba(252, 252, 252, 1.0)',
            'border-bottom: solid 1px rgb(0 0 0 / 14%)'));

        $this->style(Tools::css('.card.minimal', [' > ', '.title'], 'padding: 3px', 'font-size: 14px',
            'text-transform: uppercase'));

        $this->style(Tools::css('.card', [' > ', '.content'], 'padding: 5px'));

        $this->style(Tools::css('.card.minimal', [' > ', '.content'], 'font-size: 9px'));
    }

    /**
     * Creates a title.
     * @param bool $editable If the title should be editable.
     * @return HtmlView
     */
    public function title(bool $editable = false) : HtmlView {
        $titleView = (!$editable ? new HtmlView('title') : new EditableHtmlView('title'));
        $this->add($titleView);
        return $titleView;
    }

    /**
     * Creates a content view.
     * @return HtmlView
     */
    public function content() : HtmlView {
        $contentView = new HtmlView('content');
        $this->add($contentView);
        return $contentView;
    }

}
?>