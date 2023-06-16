<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class PanelItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class PanelItemHtmlView extends HtmlView {

    /**
     * PanelItemHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('panel-item'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex', 'flex-wrap: wrap',
            'padding-bottom: 20px'));
        $this->style(Tools::css($coreSelector, ':nth-child(even)',
            'background: var(--fine-system-background)'));
        $this->style(Tools::css($coreSelector, ':nth-child(odd)',
            'background: var(--neutral)'));
    }

    // Short cuts

    /**
     * @return ?PanelHtmlView
     */
    public function parent() : ?PanelHtmlView {
        $p = $this->getParent();
        if ($p instanceof PanelHtmlView) {
            return $p;
        } else {
            return null;
        }
    }

}
?>