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
     * Create CSS strings with the same content.
     * @param string[]|string $common Common basic selectors.
     * @param string[]|string[][] $selectors The specific selectors.
     * @param string[]|string ...$content The shared CSS styles.
     * @return string[]
     */
    public static function mCss(array|string $common, array $selectors, string ...$content) : array {
        if (is_array($common)) $common = implode($common);
        return array_map(function (array|string $selector) use ($common, $content) : string {
            return $common . (is_array($selector) ? implode($selector) : $selector) .
                '{' . implode(';', $content) . ';}';
        }, $selectors);
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

    /**
     * Splits a text up into chunks.
     * @param string $text The text.
     * @param int $chunkLength The expected maximum size of chunks.
     * @return array
     */
    public static function breakTextIntoChunks(string $text, int $chunkLength = 20) : array {
        $chunks = [];
        $tmpChunks = array_map(function (string $s) {
            return ' ' . $s;
        }, explode(' ', $text));
        $tmpChunks = array_reduce($tmpChunks, function (array $chunks, string $s) {
            $c = explode('-', $s);
            if (count($c) >= 2) {
                for ($i = 0; $i < count($c) - 1; $i++) {
                    $chunks[] = $c[$i] . '-';
                }
            }
            $chunks[] = $c[array_key_last($c)];
            return $chunks;
        }, []);
        $curChunk = '';
        foreach ($tmpChunks as $tmpChunk) {
            if (strlen($curChunk) + strlen($tmpChunk) <= $chunkLength) {
                $curChunk .= ($curChunk === '' ? ltrim($tmpChunk) : $tmpChunk);
            } else if ($curChunk === '') {
                // The current word is longer than the available space.
                $chunks[] = ltrim($tmpChunk);
            } else {
                $chunks[] = $curChunk;
                if (strlen($tmpChunk) > $chunkLength) {
                    $chunks[] = ltrim($tmpChunk);
                    $curChunk = '';
                } else {
                    $curChunk = ltrim($tmpChunk);
                }
            }
        }
        if ($curChunk !== '') $chunks[] = $curChunk;
        return $chunks;
    }
}
?>