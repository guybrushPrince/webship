<?php

namespace webship\inputs;

use ship\interoperability\IShippable;
use ship\interoperability\ShipperRegister;
use webship\core\HtmlView;

/**
 * View RadioOptionHtmlView.
 *
 * <p>A view representing the option of a {@link MultipleSwitchInputHtmlView}.</p>
 * <p><em>This view should be used only from a {@link MultipleSwitchInputHtmlView}.</em></p>
 *
 * There is no figure available.
 *
 * @see MultipleSwitchInputHtmlView
 * @name RadioOptionHtmlView
 * @category inputs
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     No examples.
 */
class RadioOptionHtmlView extends HtmlView {

    /**
     * The id.
     * @var string
     */
    private string $id;

    /**
     * The id of the parent.
     * @var string
     */
    private string $parentId;

    /**
     * The value.
     * @var string|null
     */
    private string|null $value;

    /**
     * Selected.
     * @var bool
     */
    private bool $selected;

    /**
     * Is it disabled?
     * @var bool
     */
    private bool $disabled = false;

    /**
     * Constructor.
     * @param string $optionId The id of this option.
     * @param string $parentId The id of the parent container.
     * @param IShippable|string|int|null $value The value.
     * @param string[]|string $classes Some classes.
     * @param bool $selected Whether this option is selected.
     */
    private function __construct(string $optionId, string $parentId, IShippable|string|int|null $value,
                                 bool $selected = false, array|string $classes = []) {
        parent::__construct($classes);
        if ($value instanceof IShippable ||
            (!is_string($value) && !is_int($value) && !is_float($value) && !is_numeric($value) && !is_null($value))) {
            $value = ShipperRegister::getObjectString($value);
        }
        $this->id = $optionId;
        $this->parentId = $parentId;
        $this->value = $value;
        $this->selected = $selected;
    }

    /**
     * Create a radio option.
     * @param string $optionId The id of this option.
     * @param string $parentId The id of the parent container.
     * @param IShippable|string|int|null $value The value.
     * @param string[]|string $classes Some classes.
     * @param bool $selected Whether this option is selected.
     * @return RadioOptionHtmlView
     */
    public static function create(string $optionId, string $parentId, IShippable|string|int|null $value,
                                  bool $selected = false, array|string $classes = []) : RadioOptionHtmlView {
        return new RadioOptionHtmlView($optionId, $parentId, $value, $selected, $classes);
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $open = '<input type="radio" id="' . $this->id . '" name="' . $this->parentId . '" ' .
            'value="' . (($this->value === null) ? 'null' : $this->value) . '"' . ($this->selected ? ' checked' : '') .
            ($this->disabled ? 'disabled' : '') . '/>';
        $open .= '<label for="' . $this->id . '">';
        return $open;
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</label>';
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

    /**
     * Enable/disable the switch.
     * @param bool $disabled Whether it shall be disabled or not.
     * @return $this
     */
    public function disabled(bool $disabled = true) : static {
        $this->disabled = $disabled;
        return $this;
    }
}
?>