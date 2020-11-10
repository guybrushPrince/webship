<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class CloserHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class CloserHtmlView extends HtmlView {

    /**
     * CloserHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('closer'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex', 'align-items: center'));

        $this->style(Tools::css($coreSelector, '::after', 'content: \'X\'',
            'width: 100%', 'text-align: center', 'cursor: pointer'));

        $this->style(Tools::css($coreSelector, ':hover', 'font-weight: bold'));
    }

    // Short cuts

    /**
     * Return a new closer html view.
     * @param array|string $classes Classes.
     * @return CloserHtmlView
     */
    public static function _new($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new CloserHtmlView($classes);
    }

}
?>