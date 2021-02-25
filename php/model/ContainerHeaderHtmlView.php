<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ContainerHeaderHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ContainerHeaderHtmlView extends HtmlView {

    /**
     * ContainerHeaderHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('container-header'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'font-size: 20px',
            'text-transform: uppercase', 'font-weight: lighter', 'width: 100%',
            'box-sizing: border-box'));
    }

    // Short cuts

    /**
     * Get the parent.
     * @return ?ContainerHtmlView
     */
    public function parent() : ?ContainerHtmlView {
        $p = $this->getParent();
        if ($p instanceof ContainerHtmlView) {
            return $p;
        } else {
            return null;
        }
    }

}
?>