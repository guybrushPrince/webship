<?php

namespace webship\interoperability;

use ship\interoperability\IShippable;
use webship\core\HtmlView;
use webship\dashboards\DashboardHtmlView;
use webship\dashboards\DashboardItemHtmlView;

/**
 * Interface IDashboardItem.
 *
 * <p>An abstract item of a {@link DashboardHtmlView} storing position and dimensional data.</p>
 *
 * @package webship
 * @subpackge php/interoperability
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
interface IDashboardItem extends IShippable {

    /**
     * Get x position.
     * @return int|null
     */
    public function getXPosition() : ?int;

    /**
     * Get y position.
     * @return int|null
     */
    public function getYPosition() : ?int;

    /**
     * Get x dimension.
     * @return int
     */
    public function getXDimension() : int;

    /**
     * Get y dimension.
     * @return int
     */
    public function getYDimension() : int;

    /**
     * Create a dashboard item html view from it.
     * @param string[]|string $classes Additional classes.
     * @return DashboardItemHtmlView
     */
    public function createView(array|string $classes = []) : DashboardItemHtmlView;

    /**
     * Creates a view representing an editor for the dashboard item.
     * @param IDashboardItem|null $item The dashboard item.
     * @param string[]|string $classes Some additional classes.
     * @return HtmlView
     */
    public function createEditorView(?IDashboardItem $item = null, array|string $classes = []) : HtmlView;

    /**
     * Get the title of the dashboard item.
     * @return string[]|string
     */
    public function getTitle() : array|string;

}
?>