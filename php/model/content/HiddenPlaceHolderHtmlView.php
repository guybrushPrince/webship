<?php

namespace webship\contents;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View HiddenPlaceHolderHtmlView.
 *
 * <p>An invisible view whose aim is to be present for missing information.</p>
 *
 * <code>
 * HiddenPlaceHolderHtmlView::create();)
 * </code>
 *
 * @package webship
 * @subpackge php/model/content
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     HiddenPlaceHolderHtmlView::create();
 */
class HiddenPlaceHolderHtmlView extends HtmlView {

    /**
     * Constructor.
     */
    private function __construct() {
        parent::__construct(['hph']);
        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.hph', [], 'display: none', 'visibility: hidden',
            'width: 0', 'height: 0', 'max-width: 0', 'max-height: 0'));
    }

    /**
     * Create the view.
     * @return HiddenPlaceHolderHtmlView
     */
    public static function create() : HiddenPlaceHolderHtmlView {
        return new HiddenPlaceHolderHtmlView();
    }

}
?>