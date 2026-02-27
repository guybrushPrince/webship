<?php

namespace webship\symbols;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View ColorHtmlView.
 *
 * <p>The background of a {@link ColorHtmlView} is painted in the color that is given as argument. The font color
 * is determined automatically. Furthermore, it allows for defining notices.</p>
 *
 * <p>A special color is <em>none</em> that omits the color.</p>
 *
 * <p>Prominent specializations are {@link TagHtmlView} and {@link UserChipHtmlView}.</p>
 *
 * <code>
 * ColorHtmlView::createIt("#123456", 'Hello World')->texts('Hello World');
 * ColorHtmlView::createIt("#654321")->texts('Hello World');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/color.png" />
 *
 * @package webship
 * @subpackge php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     ColorHtmlView::createIt("#123456", 'Hello World')->texts('Hello World');
 *     ColorHtmlView::createIt("#654321")->texts('Hello World');
 */
class ColorHtmlView extends HtmlView {

    /**
     * ColorHtmlView constructor.
     * @param string $color The color.
     * @param string[]|string $classes Some classes.
     * @param string[]|string|null $title An optional title (or in different languages).
     */
    protected function __construct(string $color, array|string $classes = [], array|string|null $title = null) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'color-v';
        parent::__construct($classes);

        if ($color === 'none') $font = '#000000';
        else $font = WebshipTools::getFontColor($color);
        $this->localStyle([
            '--ws-clr-bg: ' . $color,
            '--ws-clr-fc: ' . $font,
            '--ws-clr-dc: ' . $color
        ]);

        if ($title) {
            if (!is_array($title)) $title = [ self::getLanguage() => $title ];
            $this->notices($title);
        }

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.color-v', [], 'background: var(--ws-clr-bg) !important',
            'color: var(--ws-clr-fc)', '--dev-color: var(--ws-clr-dc)'));
    }

    /**
     * Create a color view.
     * @param string $color The color.
     * @param string[]|string|null $title An optional title.
     * @param string[]|string $classes Some classes.
     * @return ColorHtmlView
     */
    public static function createIt(string $color, array|string|null $title = null,
                                    array|string $classes = []) : ColorHtmlView {
        return new ColorHtmlView($color, $classes, $title);
    }
}
?>