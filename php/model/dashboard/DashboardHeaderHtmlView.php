<?php

namespace webship\dashboards;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View DashboardHeaderHtmlView.
 *
 * <p>Header view component for dashboard sections.</p>
 *
 * <code>
 * $dashboard = DashboardHtmlView::create(6, 4);
 * $dashboard->header()->texts('Dashboard Header');
 * </code>
 * 
 * <img src="http://localhost/coast/webship/modeldoc/dashboard.png" />
 *
 * @title DashboardHeader
 * @category dashboard
 * 
 * @package webship
 * @subpackage php/model/dashboard
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $header = new DashboardHeaderHtmlView();
 *     $dashboard->header()->texts('Dashboard Header');
 */
class DashboardHeaderHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['dashboard-header'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void  {
        self::addStyle(WebshipTools::css('.dashboard-header', [], 'font-size: 20px',
            'text-transform: uppercase', 'font-weight: lighter', 'width: 100%'));
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