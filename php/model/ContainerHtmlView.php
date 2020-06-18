<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ContainerHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ContainerHtmlView extends HtmlView {
    /**
     * ContainerHtmlView constructor.
     * @param array $classes
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('container'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex',
            'flex-wrap: wrap', 'align-items: flex-start', 'width: 100%',
            'box-sizing: border-box'));
    }

    // Short cuts

    /**
     * Create a header.
     * @param array $classes A set of classes.
     * @return ContainerHeaderHtmlView
     */
    public function header(array $classes = array()) {
        $headerView = new ContainerHeaderHtmlView($classes);
        $this->add($headerView);
        return $headerView;
    }

    /**
     * Create an item.
     * @param array $classes A set of classes.
     * @return ContainerItemHtmlView
     */
    public function item(array $classes = array()) {
        $itemView = new ContainerItemHtmlView($classes);
        $this->add($itemView);
        return $itemView;
    }

    /**
     * Return a new container html view.
     * @param array $classes Classes.
     * @return ContainerHtmlView
     */
    public static function _new(array $classes) {
        return new ContainerHtmlView($classes);
    }
}
?>