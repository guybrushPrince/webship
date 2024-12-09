<?php

/**
 * Class TableRowHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TableRowHtmlView extends HtmlView {

    /**
     * TableRowHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['table', 'row'], $classes));
    }

    /**
     * Create a cell for the table.
     * @param string[]|string $classes Some classes.
     * @return HtmlView
     */
    public function cell(array|string $classes = []) : HtmlView {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $cellView = new HtmlView(array_merge(['cell'], $classes));
        $this->add($cellView);
        return $cellView;
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