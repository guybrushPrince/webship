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
}
?>