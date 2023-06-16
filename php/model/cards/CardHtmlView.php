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
            'background-color: var(--neutral)', '-webkit-transition: -webkit-box-shadow .25s',
            'transition: box-shadow .25s', 'border-radius: 2px',
            '-webkit-box-shadow: var(--shadow-flow-1)',
            'box-shadow: var(--shadow-flow-1)'));

        $this->style(Tools::css('.card', [':hover'],
            '-webkit-box-shadow: var(--shadow-flow-1-hover)',
            'box-shadow: var(--shadow-flow-1-hover)'));

        $this->style(Tools::css('.card.disabled', '', 'opacity: 0.5'));
        $this->style(Tools::css('.card.disabled', [':hover'],
            '-webkit-box-shadow: var(--shadow-flow-1)',
            'box-shadow: var(--shadow-flow-1)'));

        $this->style(Tools::css('.card', [' > ', '.title'], 'color: var(--main-font)',
            'width: 100%', 'max-width: 100%', 'padding: 24px', 'font-size: 24px',
            'font-weight: 300', 'box-sizing: border-box', 'background: var(--card-title-background)',
            'border-bottom: solid 1px var(--card-title-border)'));

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
     * @param string|string[] $classes Some classes.
     * @return HtmlView
     */
    public function content($classes = []) : HtmlView {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $contentView = new HtmlView(array_merge($classes, ['content']));
        $this->add($contentView);
        return $contentView;
    }

}
?>