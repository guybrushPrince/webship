<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class CircleHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class CircleHtmlView extends HtmlView {

    /**
     * CircleHtmlView constructor.
     * @param string $target The target.
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('circle'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'width: 50px', 'border-radius: 25px'));
    }

    // Short cuts

    /**
     * Return a new circle html view.
     * @param array $classes Classes.
     * @return CircleHtmlView
     */
    public static function _new(array $classes) {
        return new CircleHtmlView($classes);
    }

}
?>