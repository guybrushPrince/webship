<?php

namespace webship\tables;

use ContainerHtmlView;
use HtmlView;
use Tools;

/**
 * View TableHtmlView.
 *
 * <p>Displays a table created of some sub views.</p>
 *
 * <code>
 * $table = TableHtmlView::createIt();
 * $headerRow = $table->header()->stretch();
 * $headerRow->cell()->texts('column 1');
 * $headerRow->cell()->texts('column 2');
 * $headerRow->cell()->texts('column 3');
 * $tableRow1 = $table->row()->stretch();
 * $tableRow1->cell()->texts('cell 1');
 * $tableRow1->cell()->texts('cell 2');
 * $tableRow1->cell()->texts('cell 3');
 * $tableRow2 = $table->row()->stretch();
 * $tableRow2->cell()->texts('cell 4');
 * $tableRow2->cell()->texts('cell 5');
 * $tableRow2->cell()->texts('cell 6');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/table.png" />
 *
 * @package webship
 * @subpackge php/model/tables
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz, Franziska Lüder
 * @version 1.2.0
 *
 * @view
 * @examples
 *     $table = TableHtmlView::createIt();
 *     $headerRow = $table->header()->stretch();
 *     $headerRow->cell()->texts('column 1');
 *     $headerRow->cell()->texts('column 2');
 *     $headerRow->cell()->texts('column 3');
 *     $tableRow1 = $table->row()->stretch();
 *     $tableRow1->cell()->texts('cell 1');
 *     $tableRow1->cell()->texts('cell 2');
 *     $tableRow1->cell()->texts('cell 3');
 *     $tableRow2 = $table->row()->stretch();
 *     $tableRow2->cell()->texts('cell 4');
 *     $tableRow2->cell()->texts('cell 5');
 *     $tableRow2->cell()->texts('cell 6');
 */
class TableHtmlView extends ContainerHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    protected function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['table'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void { }

    /**
     * create a TableHtmlView
     * @param string[]|string $classes Some classes.
     * @return TableHtmlView
     */
    public static function createIt(array|string $classes = []): TableHtmlView {
        return new TableHtmlView($classes);
    }


    /**
     * Create a header.
     * @param string[]|string $classes A set of classes.
     * @return TableRowHtmlView
     */
    public function header(array|string $classes = []): TableRowHtmlView {
        $classes = !is_array($classes) ? [ $classes ] : $classes;
        $headerView = TableRowHtmlView::create(array_merge(['header'], $classes));
        $this->add($headerView);
        return $headerView;
    }

    /**
     * Create a row.
     * @param string[]|string $classes A set of classes.
     * @return TableRowHtmlView
     */
    public function row(array|string $classes = []): TableRowHtmlView {
        $rowView = TableRowHtmlView::create($classes);
        $this->add($rowView);
        return $rowView;
    }
}
?>