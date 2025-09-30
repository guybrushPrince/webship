<?php

/**
 * View ContentButtonHtmlView.
 *
 * <p>A button that allows for showing small configuration things by first press and accept those configurations by
 * a second press. The used button is a {@link StandardButtonHtmlView} and, therefore, all its possibilities are
 * available for this content button as well.</p>
 * <img src="http://localhost/coast/webship/modeldoc/contentbutton.png" />
 *
 * <code>
 * $contentButton = ContentButtonHtmlView::create('example-1');
 * $contentButton->button()->img('/webship/pic/help.svg')->texts('Press me!');
 * $contentButton->content(TagHtmlView::create('Hello World!'));
 * </code>
 *
 * @package webship
 * @subpackge php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.3.0
 *
 * @view
 * @examples
 *     $contentButton = ContentButtonHtmlView::create('example-1');
 *     $contentButton->button()->img('/webship/pic/help.svg')->texts('Press me!');
 *     $contentButton->content(TagHtmlView::create('Hello World!'));
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
     * The overlay view.
     * @var HtmlView
     */
    private HtmlView $overlay;

    /**
     * ContentButton constructor.
     * @param string $id The id of the button.
     * @param string[]|string $classes Some classes.
     * @param string[]|string $contentClasses Additional classes for the content.
     */
    private function __construct(string $id, array|string $classes = [], array|string $contentClasses = []) {
        parent::__construct(array_merge(['content-button'], $contentClasses));
        $this->overlay = new HtmlView('overlay');
        $overlay = $this->add($this->overlay)->ref('overlay');
        $this->button = StandardButtonHtmlView::create($id, $classes);
        $this->add($this->button);
        $this->content = new ContainerHtmlView(['ver', 'gap5', 'content']);
        $this->add($this->content);
        $control = ControlPanelHtmlView::create(1, 1, false)->fix();
        $this->content->add($control);
        $closer = $control->addMini((new CloserHtmlView('close-' . $id))->mini());
        $closer->protocol(Event::CLICK)->removeClass($this, 'active');

        $overlay->protocol(Event::CLICK)->toggleClass($this, 'active');

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.content-button', '', 'position: relative',
            '--hover: var(--dark-system-background)', '--bottom-distance: 1rem'));
        self::addStyle(Tools::css('.content-button', [' > ', 'form'], 'padding: 0', 'margin: 0',
            'display: inherit', 'width: inherit', 'height: inherit', 'box-sizing: border-box'));
        self::addStyle(Tools::css('.content-button', [' > ', 'form', ' > ', '.overlay'],
            'width: 100%', 'height: 100%', 'position: absolute', 'top: 0', 'left: 0', 'cursor: pointer',
            'z-index: 1'));
        self::addStyle(Tools::css('.content-button', [' > ', 'form', ' > ', '.overlay:hover', ' + ', 'button'],
            'background: var(--hover)'));
        self::addStyle(Tools::css('.content-button.active', [' > ', 'form', ' > ', '.overlay'],
            'display: none'));
        self::addStyle(Tools::css('.content-button', [' > ', 'form', ' > ', '.content'], 'display: none',
            'width: 100%', 'position: absolute', 'background: var(--light-system-background)',
            'border: 1px solid var(--dark-system-background)', 'border-radius: 3px', 'padding: 5px 25px 5px 5px;',
            '-webkit-box-shadow: var(--shadow-flow-1)', 'box-shadow: var(--shadow-flow-1)',
            'min-width: 170px', 'z-index: 100'));
        self::addStyle(Tools::css('.content-button.active', [' > ', 'form', ' > ', '.content'],
            'display: flex'));
    }

    /**
     * Create a new content button.
     * @param string $id The id of the button.
     * @param string[]|string $classes Some classes.
     * @param string[]|string $contentClasses Additional classes for the content.
     * @return ContentButtonHtmlView
     */
    public static function create(string $id, array|string $classes = [],
                                  array|string $contentClasses = []) : ContentButtonHtmlView {
        return new ContentButtonHtmlView($id, $classes, $contentClasses);
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

    /**
     * @inheritDoc
     */
    public function notice(int $language, string $text) : static {
        $this->button->notice($language, $text);
        $this->overlay->notice($language, $text);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function notices(array|string $texts) : static {
        $this->button->notices($texts);
        $this->overlay->notices($texts);
        return $this;
    }
}
?>