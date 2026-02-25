<?php

namespace webship\symbols;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View TagHtmlView.
 *
 * <p>Presents a colored tag where the color is detected automatically or can be defined.</p>
 * <img src="http://localhost/coast/webship/modeldoc/tag.png" />
 *
 * <code>TagHtmlView::create("Hallo Welt");</code>
 * <code>TagHtmlView::create([Language::ENGLISH => 'Hello World', Language::GERMAN => 'Hallo Welt']);</code>
 *
 * @package webship
 * @subpackge php/model/symbols
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     TagHtmlView::create("Hallo Welt");
 *     TagHtmlView::create([Language::ENGLISH => 'Hello World', Language::GERMAN => 'Hallo Welt']);
 */
class TagHtmlView extends ColorHtmlView {

    /**
     * Constructor.
     * @param string $tag The tag.
     * @param HtmlView|null $content An alternative content.
     * @param string|null $color An alternative color (instead of the derived one).
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string $tag, ?HtmlView $content = null, ?string $color = null,
                                 array|string $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'ctag';
        if (is_null($color)) $color = WebshipTools::textToColor($tag);
        parent::__construct($color, $classes, $tag);

        if (!$content) $this->container(['ver', 'vcent', 'hcent', 'h100'])->texts($tag);
        else $this->add($content);

        self::addCSS();
    }

    /**
     * Generate the CSS code.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.ctag', [], 'padding: 0 5px', 'border-radius: 5px',
            'width: fit-content', 'width: -moz-fit-content', 'font-weight: bold', 'letter-spacing: 2px',
            'box-sizing: border-box', 'height: 30px', 'display: flex', 'flex-direction: row',
            'align-items: center'));
        self::addStyle(WebshipTools::css('.ctag.small', [], 'border-radius: 3px',
            'width: fit-content', 'width: -moz-fit-content', 'font-weight: normal', 'letter-spacing: normal',
            'height: 20px'));
    }

    /**
     * Create a tag.
     * @param string[]|string $tag The tag.
     * @param HtmlView|null $content An alternative content (instead of the text).
     * @param string|null $color An alternative color (instead of the derived one).
     * @param string[]|string $classes Some classes.
     * @return TagHtmlView
     */
    public static function create(array|string $tag, ?HtmlView $content = null, ?string $color = null,
                                  array|string $classes = []) : TagHtmlView {
        if (is_array($tag)) $tag = $tag[self::getLanguage()];
        return new TagHtmlView($tag, $content, $color, $classes);
    }

    /**
     * Set the tag small.
     * @return $this
     */
    public function small() : static {
        $this->addClass('small');
        return $this;
    }
}
?>