<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class DashboardItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DashboardItemHtmlView extends HtmlView {

    /**
     * DashboardItemHtmlView constructor.
     * @param array $classes
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('dashboard-item'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex', 'flex-wrap: wrap',
            'padding: 5px'));

        $this->style(Tools::css($coreSelector, array(' > ' , '*'), 'padding: 10px'));

        $this->style(Tools::css($coreSelector, array(' > ' , '.closer'), 'position: absolute',
            'right: 0', 'top: 0'));
    }

    // Short cuts

    /**
     * @return DashboardHtmlView
     */
    public function parent() {
        $p = $this->getParent();
        if ($p instanceof DashboardHtmlView) {
            return $p;
        } else {
            return null;
        }
    }

    /**
     * Generate a closer view.
     * @param array $classes The classes.
     * @return CloserHtmlView
     */
    public function closer(array $classes = array()) {
        $closer = new CloserHtmlView($classes);
        $this->add($closer);
        return $closer;
    }

    /**
     * Create a view from the dashboard items.
     * @param DashboardItem $item The dashboard item.
     * @param array $classes (optional) The classes.
     * @return DashboardItemHtmlView
     */
    public static function _new(DashboardItem $item, array $classes = array()) {
        return $item->createView($classes);
    }
}
?>