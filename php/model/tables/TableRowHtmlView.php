<?php

namespace webship\tables;

use ContainerHtmlView;
use HtmlView;
use Tools;

/**
 * View TableRowHtmlView.
 *
 * <p>Displays a row of cells in a table.</p>
 *
 * <code>
 * $tableRow = $table->row()->stretch();
 * $tableRow->cell()->texts("cell 1");
 * $tableRow->cell()->texts("cell 2");
 * $tableRow->cell()->texts("cell 3");
 * </code>
 *
 * @package webship
 * @subpackge php/model/tables
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz, Franziska Lüder
 * @version 1.2.0
 *
 * @view
 * @examples
 *     $tableRow = $table->row()->stretch();
 *     $tableRow->cell()->texts("cell 1");
 *     $tableRow->cell()->texts("cell 2");
 *     $tableRow->cell()->texts("cell 3");
 */
class TableRowHtmlView extends ContainerHtmlView {

    /**
     * TableRowHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['tr', 'single', 'p5'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.table > .tr.stretch', [' > ', '.cell'] ,'width: inherit'));
        self::addStyle(Tools::css('.table > .tr.header', [], 'font-weight: bold'));
    }
    /**
     * Create a row for a table.
     * @param string[]|string $classes Some classes.
     * @return TableRowHtmlView
     */
    public static function create(array|string $classes=[]) : TableRowHtmlView {
        return new TableRowHtmlView($classes);
    }
    /**
     * Create a cell for the table.
     * @param string[]|string $classes Some classes.
     * @return ContainerHtmlView
     */
    public function cell(array|string $classes = []) : ContainerHtmlView {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $cellView = ContainerHtmlView::createIt((array_merge([ 'cell' ], $classes)));
        $this->add($cellView);
        return $cellView;
    }

    /**
     * Stretches each of the children so that 100% of width is reached.
     * @return $this
     */
    public function stretch() : static {
        $this->addClass('stretch');
        return $this;
    }

    /**
     * Get the parent view.
     * @return ?TableHtmlView|?HtmlView
     */
    public function parent() : TableHtmlView|HtmlView|null {
        return $this->getParent();
    }
}
?>