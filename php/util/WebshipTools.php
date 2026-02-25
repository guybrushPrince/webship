<?php

namespace webship;

use ship\interoperability\ShippingTools;
use webship\core\HtmlView;

/**
 * Class WebshipTools.
 *
 * <p>Provides reused functions in most {@link HtmlView} of Webship.</p>
 *
 * @package webship
 * @subpackge php/util
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class WebshipTools extends ShippingTools {

    /**
     * Create a CSS string.
     * @param string $selector The selector.
     * @param string[]|string $subSelector The sub selector(s).
     * @param string[]|string ...$content The CSS styles.
     * @return string
     */
    public static function css(string $selector, array|string $subSelector, string ...$content) : string {
        if (!is_array($subSelector)) $subSelector = [ $subSelector ];
        return $selector . implode($subSelector) . '{' . implode(';', $content) . ';}';
    }

    /**
     * Converts a text to a (HTML/hex) color.
     * @param string|null $text The text.
     * @return string
     */
    public static function textToColor(string|null $text) : string {
        return '#' . substr(md5(($text !== null ? $text : 'REP')), 0, 6);
    }

    /**
     * Determines whether the font color should be white or black for a given
     * background color.
     * @param string $color A hex color (such as #ABCDEF)
     * @return string
     */
    public static function getFontColor(string $color) : string {
        $hex = substr($color, 1);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return ($r * 0.299 + $g * 0.587 + $b * 0.114) * 255 > 150 ? '#ffffff' : '#000000';
    }
}
?>