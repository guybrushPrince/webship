<?php

namespace webship\inputs;

use ship\interoperability\IShippable;
use ship\interoperability\ShipperRegister;
use ship\views\TextView;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View OptionHtmlView.
 *
 * <p>An option of a {@link \SelectHtmlView} or more specific view.</p>
 * <p><em>This view should only be used as an option of a {@link \SelectHtmlView}.</em></p>
 *
 * There is no figure to show.
 *
 * @see SelectHtmlView, ContentSelectHtmlView, LabelledContentSelectHtmlView
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.5.0
 *
 * @view
 * @examples
 *     There is no example as this view should only be used from other views.
 */
class OptionHtmlView extends HtmlView {

    /**
     * The value.
     * @var string
     */
    private string $value;

    /**
     * Selected.
     * @var bool
     */
    private bool $selected;

    /**
     * The text view.
     * @var TextView
     */
    private TextView $textView;

    /**
     * Constructor.
     * @param array|IShippable|string|int|float|null $value The value.
     * @param string[]|string $classes Some classes.
     * @param bool $selected Whether this option is selected.
     */
    protected function __construct(array|IShippable|string|int|float|null $value, bool $selected = false,
                                   array|string $classes = []) {
        parent::__construct(array_merge([ 'option' ], $classes));
        if ($value instanceof IShippable ||
            (!is_string($value) && !is_int($value) && !is_float($value) && !is_numeric($value) && !is_null($value) &&
                !is_array($value))) {
            $value = ShipperRegister::getObjectString($value);
        } else if (is_array($value)) {
            $value = WebshipTools::jsonEncode($value);
        } else if (is_null($value)) $value = 'null';
        $this->value = $value;
        $this->selected = $selected;
    }

    /**
     * Create an option.
     * @param array|IShippable|string|int|float|null $value The value.
     * @param string[]|string $classes Some classes.
     * @param bool $selected Whether this option is selected.
     * @return OptionHtmlView
     */
    public static function create(array|IShippable|string|int|float|null $value, bool $selected = false,
                                  array|string $classes = []) : OptionHtmlView {
        return new OptionHtmlView($value, $selected, $classes);
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
    public function texts(array|string|null $texts = null) : HtmlView|TextView|static {
        parent::texts($texts);
        $this->textView = parent::texts();
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