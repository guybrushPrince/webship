<?php

namespace webship\tables;

use HtmlView;
use Tools;

/**
 * View TableRowHtmlView.
 *
 * <p>Displays a row of cells in a table.</p>
 *
 * <code>
 * $tablerow1 = $table->row('stretch');
 * $tablerow1->cell()->texts("cell 1");
 * $tablerow1->cell()->texts("cell 2");
 * $tablerow1->cell()->texts("cell 3");
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
 * $tablerow1 = $table->row('stretch');
 * $tablerow1->cell()->texts("cell 1");
 * $tablerow1->cell()->texts("cell 2");
 * $tablerow1->cell()->texts("cell 3");
 */
class TableRowHtmlView extends HtmlView {

    /**
     * TableRowHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['tr'], $classes));
        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.table > .tr', ['.vcent'], 'align-items: center'));
        self::addStyle(Tools::css('.table > .tr', ['.vbot'], 'align-items: flex-end'));
        self::addStyle(Tools::css('.table > .tr > ',['.cell.hend'], 'display: flex',
            'justify-content: flex-end', 'justify-items: flex-end'));
        self::addStyle(Tools::css('.table > .tr > ', ['.cell.hcent'], 'display: flex',
            'justify-content: center', 'justify-items: center'));
        self::addStyle(Tools::css('.table > .tr.stretch > ', '.cell' ,'width: inherit'));
    }
    /**
     * Create a row for the table
     */
    public static function create(array|string $classes=[]): TableRowHtmlView {
        return new TableRowHtmlView($classes);
    }
    /**
     * Create a cell for the table.
     * @param string[]|string $classes Some classes.
     * @return HtmlView
     */
    public function cell(array|string $classes = []): HtmlView {
        $classes = is_array($classes) ? $classes : [$classes];
        $cellView = new HtmlView(array_merge(['cell'], $classes));
        $this->add($cellView);
        return $cellView;
    }

    /**
     * Get the parent view.
     * @return ?TableHtmlView|?HtmlView
     */
    public function parent(): TableHtmlView|HtmlView|null {
        return $this->getParent();
    }

}

?>