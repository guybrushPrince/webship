<?php

/**
 * View AreaHtmlView.
 *
 * @package webship
 * @subpackge php/model/containers
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class AreaHtmlView extends ContainerHtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes.
     */
    private function __construct(string|array $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'c-area';
        $this->classes[] = 'ver';
        $this->classes[] = 'p20';

        self::addCSS();
    }

    /**
     * Generate the CSS code.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.c-area', [], 'background: var(--ultra-light-system-background)',
            'border-radius: 10px', 'margin-top: 10px', 'box-sizing: border-box',
            'border: solid 1px var(--system-background)', 'height: -moz-fit-content'));
        self::addStyle(Tools::css('.c-area', [' > ', '.sub-title'], 'padding: 0',
            'margin-bottom: 10px'));
        self::addStyle(Tools::css('.c-area.fit', [], 'height: fit-content'));
        self::addStyle(Tools::css('.c-area.invisible', [], 'display: none'));

        self::addStyle(Tools::css('.c-area.small', [], 'background: none',
            'border-radius: inherit', 'border: inherit'));
        self::addStyle(Tools::css('.c-area.small', [' > ', ':not(.sub-title)'],
            'border-radius: 10px', 'border: solid 1px var(--system-background)'));
        self::addStyle(Tools::css('.c-area.small', [' > ', '.sub-title'], 'margin-bottom: 0',
            'font-size: 12px', 'color: var(--font-light)'));
    }

    /**
     * Create an area.
     * @param string|string[]|null $header An optional header of the area.
     * @param string|string[] $classes Some classes.
     * @return AreaHtmlView
     */
    public static function create(string|array|null $header = null, string|array $classes = []) : AreaHtmlView {
        $view = new AreaHtmlView($classes);
        if ($header) {
            if (is_string($header)) $header = [ self::getLanguage() => $header ];
            $view->header()->text(self::getLanguage(), $header[self::getLanguage()]);
        }
        return $view;
    }

    /**
     * @inheritDoc
     */
    public function header(array|string $classes = []) : HtmlView|TextView {
        $title = new SubTitleHtmlView();
        $this->add($title);
        return $title->texts();
    }

    /**
     * @inheritDoc
     */
    public function item(array|string $classes = []) : HtmlView {
        $view = new HtmlView();
        $this->add($view);
        return $view;
    }

    /**
     * @inheritDoc
     */
    public static function _new(array|string $classes = []) : static {
        return new AreaHtmlView($classes);
    }

    /**
     * Set the height to fit the content.
     * @return $this
     */
    public function fit() : static {
        $this->classes[] = 'fit';
        return $this;
    }

    /**
     * Produces a finer area.
     * @return $this
     */
    public function small(): static {
        $this->classes[] = 'small';
        return $this;
    }

}
?>