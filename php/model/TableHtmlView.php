<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class TableHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TableHtmlView extends HtmlView {
    /**
     * ContainerHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(['table'] + $classes);

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex',
            'flex-wrap: wrap', 'flex-direction: column', 'align-items: flex-start', 'width: 100%',
            'box-sizing: border-box'));
        $this->style(Tools::css($coreSelector, [' > ', '.row'], 'display: flex',
            'flex-wrap: nowrap', 'flex-direction: row', 'padding: 5px', 'box-sizing: border-box'));
        $this->style(Tools::css($coreSelector, [' > ', '.row.header'], 'font-weight: bold'));
    }

    // Short cuts

    /**
     * Create a header.
     * @param array|string $classes A set of classes.
     * @return TableRowHtmlView
     */
    public function header($classes = array()) {
        $headerView = new TableRowHtmlView(['header'] + $classes);
        $this->add($headerView);
        return $headerView;
    }

    /**
     * Create a row.
     * @param array|string $classes A set of classes.
     * @return TableRowHtmlView
     */
    public function row($classes = array()) {
        $rowView = new TableRowHtmlView($classes);
        $this->add($rowView);
        return $rowView;
    }

    /**
     * Return a new table html view.
     * @param array|string $classes Classes.
     * @return TableHtmlView
     */
    public static function _new($classes = array()) {
        return new TableHtmlView($classes);
    }
}
?>