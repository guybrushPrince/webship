<?php

namespace webship\titles;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View SubTitleHtmlView.
 *
 * <p>A standardized subtitle for any other view.</p>
 * <img src="http://localhost/coast/webship/modeldoc/subtitle.png" />
 *
 * <code>SubTitleHtmlView::create('Hello World!');</code>
 *
 * @title SubTitle
 * @category titles
 * 
 * @package webship
 * @subpackage php/model
 *
 * @version 1.1.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 *
 * @view
 * @examples
 *     SubTitleHtmlView::create('Hello World!');
 */
class SubTitleHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes.
     */
    private function __construct(string|array $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'sub-title';

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.sub-title', [], 'font-family: Roboto, sans-serif',
            'text-transform: none', 'font-size: 1.25em', 'font-weight: lighter', 'width: 100%',
            'width: 100%'));
    }

    /**
     * Create a subtitle view.
     * @param string[]|string|null $texts The (sub) title.
     * @param string|string[] $classes Some classes.
     * @return SubTitleHtmlView
     */
    public static function create(array|string|null $texts = null, string|array $classes = []) : SubTitleHtmlView {
        $view = new SubTitleHtmlView($classes);
        if (!is_null($texts)) $view->texts($texts);
        return $view;
    }
}
?>