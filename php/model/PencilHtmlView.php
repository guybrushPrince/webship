<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class PencilHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class PencilHtmlView extends HtmlView {

    /**
     * PencilHtmlView constructor.
     * @param string $target The target.
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('pencil'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex', 'align-items: center'));

        $this->style(Tools::css($coreSelector, '::after', 'content: "\270E"',
            'width: 100%', 'text-align: center', 'cursor: pointer'));

        $this->style(Tools::css($coreSelector, ':hover', 'font-weight: bold'));
    }

    // Short cuts

    /**
     * Return a new pencil html view.
     * @param array $classes Classes.
     * @return PencilHtmlView
     */
    public static function _new(array $classes) {
        return new PencilHtmlView($classes);
    }

}
?>