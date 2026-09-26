<?php

namespace webship\symbols;

use webship\core\HtmlView;

/**
 * View PlusHtmlView.
 *
 * <p>Renders a plus icon (+) symbol view.</p>
 *
 * <code>
 * $plus = new PlusHtmlView();
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/plus.png" />
 *
 * @title Plus
 * @category symbols
 * 
 * @package webship
 * @subpackage php/model/symbols
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $plus = new PlusHtmlView();
 */
class PlusHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes..
     */
    private function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['plus'], $classes));
    }

    /**
     * Return a new plus html view.
     * @param string[]|string $classes Classes.
     * @return PlusHtmlView
     */
    public static function _new(array|string $classes = []) : PlusHtmlView {
        return new PlusHtmlView($classes);
    }
}
?>