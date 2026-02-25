<?php

namespace webship\controller;

use ship\controllers\ShipController;
use webship\dashboards\DashboardHtmlView;

/**
 * Class DashboardController.
 *
 * <p>Provides general dashboard functionalities (APIs).</p>
 *
 * @see ShipController
 * @package webship
 * @subpackge php/basics
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class DashboardController extends ShipController {

    /**
     * Loads a dashboard html view.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     * @param bool $init If it is initialed or not.
     * @return DashboardHtmlView
     */
    public function loadDashboard(int $cols = 6, int $rows = 4, bool $init = false) : DashboardHtmlView {
        $dashboard = DashboardHtmlView::create($cols, $rows, $init);
        $dashboardHandler = DashboardHtmlView::getDashboardHandler();
        if (!is_null($dashboardHandler) && $dashboardHandler->isSafe()) {
            $dashboardHandler->addContextMenu($dashboard);
        }
        return $dashboard;
    }

    /**
     * Loads a dashboard html view.
     * @without
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     * @param bool $init If it is initialed or not.
     * @return DashboardHtmlView
     */
    public function loadDashboardUnsafe(int $cols = 6, int $rows = 4, bool $init = false) : DashboardHtmlView {
        return DashboardHtmlView::create($cols, $rows, false);
    }

    /**
     * @inheritDoc
     * @ignore
     */
    public function getScope() : ?string {
        return DashboardFunctions::environment();
    }

}
?>