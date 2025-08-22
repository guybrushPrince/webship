<?php

/**
 * Class OptionHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class OptionHtmlView extends HtmlView {

    /**
     * The value.
     * @var string
     */
    private string $value;

    /**
     * Selected.
     * @var boolean
     */
    private bool $selected;

    /**
     * The text view.
     * @var TextView
     */
    private TextView $textView;

    /**
     * OptionHtmlView constructor.
     * @param string|IShippable|null $value The value.
     * @param array|string $classes
     * @param bool $selected Whether this option is selected.
     */
    public function __construct($value, array|string $classes = [], bool $selected = false) {
        parent::__construct(array_merge(array('option'), $classes));
        if ($value instanceof IShippable ||
            (!is_string($value) && !is_int($value) && !is_float($value) && !is_numeric($value) && !is_null($value) &&
                !is_array($value))) {
            $value = ShipperRegister::getObjectString($value);
        } else if (is_array($value)) {
            $value = Tools::jsonEncode($value);
        } else if (is_null($value)) $value = 'null';
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

    /**
     * @inheritDoc
     */
    public function texts(array|string|null $texts = null) : HtmlView|TextView {
        $this->textView = parent::texts($texts);
        return $this;
    }

    /**
     * Set a text.
     * @param int $language The language.
     * @param string $text The text.
     * @return $this
     */
    public function text(int $language, string $text) : static {
        $this->textView->text($language, strip_tags($text));
        return $this;
    }

    /**
     * Get the value behind the option.
     * @return string
     */
    public function getValue() : string {
        return $this->value;
    }

    /**
     * Is the option selected?
     * @return bool
     */
    public function isSelected() : bool {
        return $this->selected;
    }
}
?>