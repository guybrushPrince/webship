<?php

namespace webship\containers;

use ship\views\TextView;
use webship\core\HtmlView;
use webship\titles\SubTitleHtmlView;
use webship\WebshipTools;

/**
 * View AreaHtmlView.
 *
 * <p>This view describes a surrounded container area with an (optional) header/title.</p>
 * 
 * <img src="http://localhost/coast/webship/modeldoc/area.png" />
 *
 * <code>AreaHtmlView::create('Hello World!');</code>
 *
 * @title Area
 * @category containers
 * 
 * @package webship
 * @subpackage php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     AreaHtmlView::create('Hello World!');
 */
class AreaHtmlView extends ContainerHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes = array_merge(['c-area', 'ver', 'p20'], $classes);
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.c-area', [], 'background: var(--ws-std-bg-ultra)',
            'border-radius: 10px', 'margin-top: 10px', 'box-sizing: border-box',
            'border: solid 1px var(--ws-std-bg)'));
        self::addStyle(WebshipTools::css('.c-area', [' > ', '.sub-title'], 'padding: 0',
            'margin-bottom: 10px'));
        self::addStyle(WebshipTools::css('.c-area.fit', [], 'height: fit-content'));
        self::addStyle(WebshipTools::css('.c-area.invisible', [], 'display: none'));

        self::addStyle(WebshipTools::css('.c-area.small', [], 'background: none',
            'border-radius: inherit', 'border: inherit'));
        self::addStyle(WebshipTools::css('.c-area.small', [' > ', ':not(.sub-title)'],
            'border-radius: 10px', 'border: solid 1px var(--ws-std-bg)'));
        self::addStyle(WebshipTools::css('.c-area.small', [' > ', '.sub-title'], 'margin-bottom: 0',
            'font-size: 12px', 'color: var(--ws-font-dgray)'));
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
            $view->header($header);
        }
        return $view;
    }

    /**
     * @inheritDoc
     */
    public function header(array|string|null $header = null, array|string $classes = []) : HtmlView|TextView|static {
        $title = SubTitleHtmlView::create();
        $this->add($title);
        if (!is_null($header)) {
            $title->texts($header);
            return $this;
        } else {
            return $title->texts();
        }
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
    public function small() : static {
        $this->classes[] = 'small';
        return $this;
    }

}
?>