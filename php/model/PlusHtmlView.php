<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class PlusHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class PlusHtmlView extends HtmlView {

    /**
     * PlusHtmlView constructor.
     * @param string $target The target.
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('plus'), $classes));
    }

    // Short cuts

}
?>