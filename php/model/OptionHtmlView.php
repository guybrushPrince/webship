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
     * OptionHtmlView constructor.
     * @param string $value The value.
     * @param array $classes
     */
    public function __construct($value, array $classes = array()) {
        parent::__construct(array_merge(array('option'), $classes));
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<option value="' . $this->value . '">';
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