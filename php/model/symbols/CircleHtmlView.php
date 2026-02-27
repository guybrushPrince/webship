<?php

namespace webship\symbols;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * Class CircleHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class CircleHtmlView extends HtmlView {

    /**
     * CircleHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['circle'], $classes));

        $this->style(WebshipTools::css('.circle', [], 'width: 50px', 'border-radius: 25px'));
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