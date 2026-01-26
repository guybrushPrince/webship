<?php

namespace webship\tables;

use HtmlView;
use Tools;

/**
 * View TableHtmlView.
 *
 * <p>Displays a table of views.</p>
 *
 * <code>
 * $table = TableHtmlView::create();
 * $headerrow = $table->header('stretch');
 * $headerrow->cell()->texts('column 1');
 * $headerrow->cell()->texts('column 2');
 * $headerrow->cell()->texts('column 3');
 * $tablerow1 = $table->row('stretch');
 * $tablerow1->cell()->texts('cell 1');
 * $tablerow1->cell()->texts('cell 2');
 * $tablerow1->cell()->texts('cell 3');
 * $tablerow2 = $table->row('stretch');
 * $tablerow2->cell()->texts('cell 4');
 * $tablerow2->cell()->texts('cell 5');
 * $tablerow2->cell()->texts('cell 6');
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
 * $table = TableHtmlView::create();
 * $headerrow = $table->header('stretch)'
 * $headerrow->cell()->texts('column 1')
 * $headerrow->cell()->texts('column 2')
 * $headerrow->cell()->texts('column 3')
 * $tablerow1 = $table->row('stretch');
 * $tablerow1->cell()->texts('cell 1');
 * $tablerow1->cell()->texts('cell 2');
 * $tablerow1->cell()->texts('cell 3');
 * $tablerow2 = $table->row('stretch');
 * $tablerow2->cell()->texts('cell 4');
 * $tablerow2->cell()->texts('cell 5');
 * $tablerow2->cell()->texts('cell 6');
 */
class TableHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    protected function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['table'], $classes));
        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.table', [''], 'display: flex',
            'flex-wrap: wrap', 'flex-direction: column', 'align-items: flex-start', 'width: 100%',
            'box-sizing: border-box'));
        self::addStyle(Tools::css('.table', [' > ', '.tr'], 'display: flex',
            'flex-wrap: nowrap', 'flex-direction: row', 'padding: 5px', 'box-sizing: border-box', 'width: 100%'));
        self::addStyle(Tools::css('.table', [' > ', '.tr.header'], 'font-weight: bold'));
    }

    /**
     * create a TableHtmlView
     * @param string|string[] $classes Some classes.
     * @return TableHtmlView
     */
    public static function create(array|string $classes=[]): TableHtmlView {
        return new TableHtmlView($classes);
    }


    /**
     * Create a header.
     * @param string|string[] $classes A set of classes.
     * @return TableRowHtmlView
     */
    public function header(array|string $classes = []): TableRowHtmlView {
        $classes = !is_array($classes) ? [$classes] : $classes;
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