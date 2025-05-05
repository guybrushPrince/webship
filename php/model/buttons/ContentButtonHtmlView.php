<?php

/**
 * View ContentButtonHtmlView.
 *
 * <p>A button that allows for showing small configuration things.</p>
 *
 * @package webship
 * @subpackge php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class ContentButtonHtmlView extends HtmlView {

    /**
     * The button view.
     * @var StandardButtonHtmlView
     */
    private StandardButtonHtmlView $button;

    /**
     * The content view.
     * @var HtmlView
     */
    private HtmlView $content;

    /**
     * ContentButton constructor.
     * @param string $id The id of the button.
     * @param string[]|string $classes Some classes.
     * @param string[]|string $contentClasses Additional classes for the content.
     */
    public function __construct(string $id, array|string $classes = [], array|string $contentClasses = []) {
        parent::__construct(array_merge(['content-button'], $contentClasses));
        $overlay = $this->add(new HtmlView('overlay'))->ref('overlay');
        $this->button = StandardButtonHtmlView::create($id, $classes);
        $this->add($this->button);
        $this->content = new HtmlView('content');
        $this->add($this->content);

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
     * @return StandardButtonHtmlView
     */
    public function button() : StandardButtonHtmlView {
        return $this->button;
    }

    /**
     * Set the content.
     * @param HtmlView $view
     * @return View
     */
    public function content(HtmlView $view) : View {
        return $this->content->add($view);
    }
}
?>