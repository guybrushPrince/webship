<?php

namespace webship\buttons;

use webship\containers\SectionHtmlView;
use webship\protocols\HtmlActionProtocol;
use webship\WebshipTools;

/**
 * View SectionLikeButtonHtmlView.
 *
 * <p>A button that looks like a section-title in {@link SectionHtmlView}.</p>
 * <img src="http://localhost/coast/webship/modeldoc/sectionlikebutton.png" />
 *
 * <code>
 * SectionLikeButtonHtmlView::create('example-1')->texts('Press me!')
 * SectionLikeButtonHtmlView::create('example-2', '/webship/pic/arrow-down-sign-to-navigate.svg')->
 *     texts('Press me!')
 * </code>
 *
 * @package webship
 * @subpackage php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     SectionLikeButtonHtmlView::create('example-1')->texts('Press me!')
 *     SectionLikeButtonHtmlView::create('example-2', '/webship/pic/arrow-down-sign-to-navigate.svg')->
 *         texts('Press me!')
 */
class SectionLikeButtonHtmlView extends ButtonHtmlView {

    /**
     * Constructor.
     * @param string|null $id The id of the button.
     * @param string|null $img A small image to be displayed behind the text.
     * @param string[]|string $classes Some classes.
     * @param string|null $type The button type.
     */
    private function __construct(?string $id = null, ?string $img = null, array|string $classes = [],
                                ?string $type = 'button') {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'sect-but';
        parent::__construct($id, $type, $classes);

        if ($img) $this->localStyle('--ws-after-image: url("' . HtmlActionProtocol::$serverURL . $img . '")');

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('button.sect-but', [],'width: 100%', 'display: flex',
            'flex-direction: row', 'padding: 0 0 16px 0', 'border: none', 'background: none', 'height: auto',
            'font-family: Roboto, sans-serif', 'text-transform: none', 'font-size: 1.25em', 'font-weight: lighter',
            'cursor: pointer'));
        self::addStyle(WebshipTools::css('button.sect-but:last-child', [],'padding: 0'));
        self::addStyle(WebshipTools::css('button.sect-but', [':after'], 'content: ""',
            'margin-left: 5px', 'background-repeat: no-repeat', 'width: 15px', 'height: 15px', 'background-size: cover',
            'background-image: var(--ws-after-image)',
            'box-sizing: border-box', 'display: inline-block', 'opacity: 0.25', 'transition: opacity 0.5s'));
        self::addStyle(WebshipTools::css('button.sect-but', [':hover', ':after'], 'opacity: 1'));
    }

    /**
     * Create a section-like button.
     * @param string|null $id The id of the button.
     * @param string|null $img A small image to be displayed behind the text.
     * @param string[]|string $classes Some classes.
     * @param string|null $type The button type.
     * @return SectionLikeButtonHtmlView
     */
    public static function create(?string $id = null, ?string $img = null, array|string $classes = [],
                                  ?string $type = 'button') : SectionLikeButtonHtmlView {
        return new SectionLikeButtonHtmlView($id, $img, $classes, $type);
    }
}
?>