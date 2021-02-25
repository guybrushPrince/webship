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
        parent::__construct(['table', 'row'] + $classes);
    }

    // Short cuts

    /**
     * Create a cell for the table.
     * @param array $classes
     * @return HtmlView
     */
    public function cell($classes = array()) {
        $cellView = new HtmlView(['cell'] + $classes);
        $this->add($cellView);
        return $cellView;
    }

    /**
     * @return ?TableHtmlView
     */
    public function parent(): ?TableHtmlView {
        return $this->getParent();
    }

}
?>