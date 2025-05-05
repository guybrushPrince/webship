<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class PlusHtmlView.
 *
 * @package webship
 * @subpackge php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class PlusHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes..
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'plus' ], $classes));
    }

}
?>