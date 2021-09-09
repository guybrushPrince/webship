<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class TableRowHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TableRowHtmlView extends HtmlView {

    /**
     * TableRowHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(['table', 'row'], $classes));
    }

    // Short cuts

    /**
     * Create a cell for the table.
     * @param array $classes
     * @return HtmlView
     */
    public function cell($classes = array()) : HtmlView {
        $classes = is_array($classes) ? $classes : array($classes);
        $cellView = new HtmlView(array_merge(['cell'], $classes));
        $this->add($cellView);
        return $cellView;
    }

    /**
     * @return ?TableHtmlView|?View
     */
    public function parent(): ?TableHtmlView {
        return $this->getParent();
    }

}
?>