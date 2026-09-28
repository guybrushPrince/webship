<?php

namespace webship\symbols;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View CircleHtmlView.
 *
 * <p>Renders a circular shape symbol view.</p>
 *
 * <code>
 * $circle = new CircleHtmlView();
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/circle.png" />
 * 
 * @title Circle
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
 *     $circle = new CircleHtmlView();
 */
class CircleHtmlView extends HtmlView {

    /**
     * CircleHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['circle'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.circle', [], 'width: 50px', 'border-radius: 25px'));
    }

    /**
     * Return a new circle html view.
     * @param string[]|string $classes Classes.
     * @return CircleHtmlView
     */
    public static function _new(array|string $classes = []) : CircleHtmlView {
        return new CircleHtmlView($classes);
    }
}
?>