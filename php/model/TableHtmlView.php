<?php

/**
 * Class TableHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TableHtmlView extends HtmlView {

    /**
     * ContainerHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(['table'], $classes));

        $this->style(Tools::css('.table', '', 'display: flex',
            'flex-wrap: wrap', 'flex-direction: column', 'align-items: flex-start', 'width: 100%',
            'box-sizing: border-box'));
        $this->style(Tools::css('.table', [' > ', '.tr'], 'display: flex',
            'flex-wrap: nowrap', 'flex-direction: row', 'padding: 5px', 'box-sizing: border-box', 'width: 100%'));
        $this->style(Tools::css('.table', [' > ', '.tr.header'], 'font-weight: bold'));
    }

    /**
     * Create a header.
     * @param string|string[] $classes A set of classes.
     * @return TableRowHtmlView
     */
    public function header(array|string $classes = []) : TableRowHtmlView {
        $headerView = new TableRowHtmlView(array_merge(['header'], $classes));
        $this->add($headerView);
        return $headerView;
    }

    /**
     * Create a row.
     * @param string[]|string $classes A set of classes.
     * @return TableRowHtmlView
     */
    public function row(array|string $classes = []) : TableRowHtmlView {
        $rowView = new TableRowHtmlView($classes);
        $this->add($rowView);
        return $rowView;
    }

    /**
     * Return a new table html view.
     * @param string|string[] $classes Some classes.
     * @return TableHtmlView
     */
    public static function _new(array|string $classes = []) : TableHtmlView {
        return new TableHtmlView($classes);
    }
}
?>