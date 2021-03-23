<?php

/**
 * Class ContentButtonHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ContentButtonHtmlView extends HtmlView {

    /**
     * ContentButton constructor.
     * @param string $id The id of the button.
     * @param array $classes The classes.
     * @param array $contentClasses Additional classes for the content.
     */
    public function __construct(string $id, $classes = array(), $contentClasses = array()) {
        parent::__construct(array_merge(['content-button'], $contentClasses));
        $overlay = $this->add(new HtmlView('overlay'))->ref('overlay');
        $this->add(new ButtonHtmlView($id, 'button', $classes));
        $this->add(new HtmlView('content'));

        $this->style(Tools::css('.content-button', '', 'position: relative'));
        $this->style(Tools::css('.content-button', [' > ', 'form'], 'padding: 0', 'margin: 0',
            'display: inherit', 'width: inherit', 'height: inherit'));
        $this->style(Tools::css('.content-button', [' > ', 'form', ' > ', '.overlay'],
            'width: 100%', 'height: 100%', 'position: absolute', 'top: 0', 'left: 0', 'cursor: pointer',
            'z-index: 1'));
        $this->style(Tools::css('.content-button.active', [' > ', 'form', ' > ', '.overlay'],
            'display: none'));
        $this->style(Tools::css('.content-button', [' > ', 'form', ' > ', '.content'], 'display: none',
            'width: 100%'));
        $this->style(Tools::css('.content-button.active', [' > ', 'form', ' > ', '.content'],
            'display: inherit'));

        $overlay->protocol(Event::CLICK)->toggleClass($this, 'active');
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $this->button()->protocol(Event::CLICK)->toggleClass($this, 'active');
        return parent::generateOpeningTag() . '<form>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</form>' . parent::generateClosingTag();
    }


    /**
     * Returns the button.
     * @return ButtonHtmlView
     */
    public function button() : ButtonHtmlView {
        return $this->subviews[1];
    }

    /**
     * Set the content.
     * @param HtmlView $view
     * @return View
     */
    public function content(HtmlView $view) : View {
        return $this->subviews[2]->add($view);
    }
}
?>