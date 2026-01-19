<?php

namespace webship\tables;

use HtmlView;
use Tools;

/**
 * Class TableRowHtmlView
 * <p>Displays a row of cells in a table.</p>
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TableRowHtmlView extends HtmlView
{

    /**
     * TableRowHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []){
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['tr'], $classes));
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.table > .tr', ['.vcent'], 'align-items: center'));
        self::addStyle(Tools::css('.table > .tr', ['.vbot'], 'align-items: flex-end'));
        self::addStyle(Tools::css('.table > .tr > .cell', ['.hend'], 'display: flex',
            'justify-content: flex-end', 'justify-items: flex-end'));
        self::addStyle(Tools::css('.table > .tr > .cell', ['.hcent'], 'display: flex',
            'justify-content: center', 'justify-items: center'));
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
    public function cell(array|string $classes = []): HtmlView{
        $classes = is_array($classes) ? $classes : [$classes];
        $cellView = new HtmlView(array_merge(['cell'], $classes));
        $this->add($cellView);
        return $cellView;
    }

    /**
     * Get the parent view.
     * @return ?TableHtmlView|?HtmlView
     */
    public function parent(): TableHtmlView|HtmlView|null{
        return $this->getParent();
    }

}

?>