<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class DashboardHeaderHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DashboardHeaderHtmlView extends HtmlView {

    /**
     * DashboardHeaderHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('dashboard-header'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'font-size: 20px', 'text-transform: uppercase',
            'font-weight: lighter', 'width: 100%'));
    }

    // Short cuts

    /**
     * Get the parent.
     * @return ?DashboardHtmlView
     */
    public function parent() : ?DashboardHtmlView {
        $p = $this->getParent();
        if ($p instanceof DashboardHtmlView) {
            return $p;
        } else {
            return null;
        }
    }

}
?>