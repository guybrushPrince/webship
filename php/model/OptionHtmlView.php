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
     * @param string|CObject|BObject $value The value.
     * @param array|string $classes
     * @param boolean $selected Whether this option is selected.
     */
    public function __construct($value, $classes = array(), $selected = false) {
        parent::__construct(array_merge(array('option'), $classes));
        if ($value instanceof CObject || $value instanceof BObject || !is_string($value) && !is_int($value)) {
            $key = $value->getKey();
            $value = (is_integer($key) ? ActionRegister::convertKey($key) : $key);
        }
        $this->value = $value;
        $this->selected = $selected;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<option value="' . $this->value . '"' . ($this->selected ? ' selected' : '') . '>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</option>';
    }

    // Short cuts

}
?>