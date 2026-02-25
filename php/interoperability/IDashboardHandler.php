<?php

namespace webship\interoperability;

use webship\dashboards\DashboardHtmlView;
use webship\dashboards\DashboardItemHtmlView;
use webship\menus\ContextMenuHtmlView;

/**
 * Interface IDashboardHandler.
 *
 * <p>A {@link DashboardHtmlView} may shall store items that are selected from a user. An implementation of
 * {@link IDashboardHandler} decouples the database-related stuff from the Webship package.</p>
 *
 * @package webship
 * @subpackge php/interoperability
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
interface IDashboardHandler {

    /**
     * Returns if the application is in safe mode (e.g., logged in) or not.
     * @return bool
     */
    public function isSafe() : bool;

    /**
     * Get the list of all dashboard items being registered.
     * @return IDashboardItem[]|null
     */
    public function getDashboardItems() : array|null;

    /**
     * Get the welcome screen.
     * @param int $cols The columns.
     * @param int $rows The rows.
     * @return DashboardItemHtmlView[]
     */
    public function getWelcomeScreen(int $cols, int $rows) : array;

    /**
     * Adds a context menu to the given dashboard.
     * @param DashboardHtmlView $dashboardHtmlView The dashboard.
     * @return ContextMenuHtmlView
     */
    public function addContextMenu(DashboardHtmlView $dashboardHtmlView) : ContextMenuHtmlView;

}
?>