<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class OptionHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class OptionHtmlView extends HtmlView {

    /**
     * The value.
     * @var string
     */
    private $value;

    /**
     * Selected.
     * @var boolean
     */
    private $selected;

    /**
     * OptionHtmlView constructor.
     * @param string $value The value.
     * @param array $classes
     * @param boolean $selected Whether this option is selected.
     */
    public function __construct($value, array $classes = array(), $selected = false) {
        parent::__construct(array_merge(array('option'), $classes));
        $this->value = $value;
        $this->selected = $selected;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<option value="' . $this->value . '"' . ($this->selected ? ' selected' : '') . '>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</option>';
    }

    // Short cuts

}
?>