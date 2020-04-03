<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class PanelHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class PanelHtmlView extends HtmlView {

    /**
     * PanelHtmlView constructor.
     * @param array $classes
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('panel'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'width: 100%', 'padding: 20px 20px',
            'box-sizing: border-box', 'border: solid 1px rgba(200, 200, 200, 1.0)'));

        $this->style(Tools::css($coreSelector, ' > .panel-item',
            'border-bottom: dashed 1px rgba(200, 200, 200, 1)'));
        $this->style(Tools::css($coreSelector, array(' > .panel-item', ':last-child'),
            'border-bottom: none'));
    }

    // Short cuts

    /**
     * Create a header.
     * @param array $classes A set of classes.
     * @return PanelHeaderHtmlView
     */
    public function header(array $classes = array()) {
        $headerView = new PanelHeaderHtmlView($classes);
        $this->add($headerView);
        return $headerView;
    }

    /**
     * Create an item.
     * @param array $classes A set of classes.
     * @return PanelItemHtmlView
     */
    public function item(array $classes = array()) {
        $itemView = new PanelItemHtmlView($classes);
        $this->add($itemView);
        return $itemView;
    }

}
?>