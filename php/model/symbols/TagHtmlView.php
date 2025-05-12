<?php

/**
 * View TagHtmlView.
 *
 * <p>Presents a colored tag.</p>
 *
 * @package webship
 * @subpackge php/model/symbols
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
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
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'ctag';
        if (is_null($color)) $color = Tools::textToColor($tag);
        parent::__construct($color, $classes, $tag);

        if (!$content) $this->texts($tag);
        else $this->add($content);

        self::addCSS();
    }

    /**
     * Generate the CSS code.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.ctag', [], 'padding: 5px', 'border-radius: 5px',
            'width: fit-content', 'width: -moz-fit-content', 'font-weight: bold', 'letter-spacing: 2px'));
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

}
?>