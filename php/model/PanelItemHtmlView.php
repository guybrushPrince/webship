<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class PanelItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class PanelItemHtmlView extends HtmlView {

    /**
     * PanelItemHtmlView constructor.
     * @param array $classes
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('panel-item'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex', 'flex-wrap: wrap',
            'padding-bottom: 20px'));
        $this->style(Tools::css($coreSelector, ':nth-child(even)',
            'background: rgba(250, 250, 250, 1.0)'));
        $this->style(Tools::css($coreSelector, ':nth-child(odd)',
            'background: rgba(255, 255, 255, 1.0)'));
    }

    // Short cuts

    /**
     * @return PanelHtmlView
     */
    public function parent() {
        $p = $this->getParent();
        if ($p instanceof PanelHtmlView) {
            return $p;
        } else {
            return null;
        }
    }

}
?>