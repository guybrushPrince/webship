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
        self::addStyle(Tools::css('.table > .tr', [' > ', '.cell'] ,
            'width: -moz-fit-content', 'width: fit-content'));
        self::addStyle(Tools::css('.table > .tr.stretch', [' > ', '.cell'] ,'width: inherit'));
        self::addStyle(Tools::css('.table.stretch > .tr', [' > ', '.cell'] ,'width: inherit'));
        self::addStyle(Tools::css('.table > .tr.stretch', [' > ', '.cell.full'] ,'grid-column: 1/-1'));
        self::addStyle(Tools::css('.table > .tr.header', [], 'font-weight: bold'));
        self::addStyle(Tools::css('.table > .tr.header.border', [], 'align-items: flex-end',
            'border-bottom: 1px solid var(--system-background)', 'padding-bottom: 4px'));
        self::addStyle(Tools::css('.table > .tr.header.border', [' > ', '.cell'], 'align-items: flex-end',
            'align-content: flex-end'));
        self::addStyle(Tools::css('.table > .tr.hoverable', [], 'transition: 0.5s background'));
        self::addStyle(Tools::css('.table > .tr.hoverable', [':hover'],
            'background: var(--light-system-background)'));
        self::addStyle(Tools::css('.table > .tr.h40px', [' > ', '.cell'],'min-height: 40px'));
    }
    /**
     * Create a row for a table.
     * @param string[]|string $classes Some classes.
     * @return TableRowHtmlView
     */
    public static function create(array|string $classes = []) : TableRowHtmlView {
        return new TableRowHtmlView($classes);
    }

    /**
     * Create a header row in the table.
     * @param bool $border The bottom border.
     * @param string[]|string $classes Some classes.
     * @return TableRowHtmlView
     */
    public static function createHeader(bool $border = false, array|string $classes = []) : TableRowHtmlView {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'header';
        if ($border) $classes[] = 'border';
        return new TableRowHtmlView($classes);
    }

    /**
     * Create a cell for the table.
     * @param string[]|string $classes Some classes.
     * @return ContainerHtmlView
     */
    public function cell(array|string $classes = []) : ContainerHtmlView {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $cellView = ContainerHtmlView::createIt(array_merge([ 'cell' ], $classes));
        $this->add($cellView);
        return $cellView;
    }

    /**
     * Create a full width cell (spanning all others.
     * @param string[]|string $classes Some classes.
     * @return ContainerHtmlView
     */
    public function full(array|string $classes = []) : ContainerHtmlView {
        $classes = is_array($classes) ? $classes : [ $classes ];
        return $this->cell(array_merge([ 'full' ], $classes));
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
     * Makes the row hoverable.
     * @return $this
     */
    public function hoverable() : static {
        $this->addClass('hoverable');
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