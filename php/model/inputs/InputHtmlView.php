<?php

namespace webship\inputs;

use ship\interoperability\IShippable;
use ship\interoperability\ShipperRegister;
use ship\views\View;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View InputHtmlView.
 *
 * <p>The most basic view to represent a Ship input field.</p>
 * <p>Usually it is more elegant to use {@link LabelledInputHtmlView} or {@link CheckboxHtmlView} instead.</p>
 *
 * <code>
 * InputHtmlView::createIt('input-1', 'text');
 * InputHtmlView::createIt('input-2', 'text')->texts('Hello World');
 * InputHtmlView::createIt('input-3', 'number')->texts(5);
 * InputHtmlView::createIt('input-4', 'checkbox');
 * InputHtmlView::createIt('input-5', 'hidden');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/input.png" />
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.6.0
 *
 * @view
 * @examples
 *     InputHtmlView::createIt('input-1', 'text');
 *     InputHtmlView::createIt('input-2', 'text')->texts('Hello World');
 *     InputHtmlView::createIt('input-3', 'number')->texts(5);
 *     InputHtmlView::createIt('input-4', 'checkbox');
 *     InputHtmlView::createIt('input-5', 'hidden');
 */
class InputHtmlView extends HtmlView {

    /**
     * An id.
     * @var string|null
     */
    private ?string $id;

    /**
     * The name
     * @var string
     */
    private string $name;

    /**
     * The type
     * @var string
     */
    private string $type;

    /**
     * A specific value for checkboxes.
     * @var string|null
     */
    private ?string $value = null;

    /**
     * If the value shall be forced to return.
     * @var bool
     */
    private bool $force = false;

    /**
     * The minimum length of the content.
     * @var int|float|string|null
     */
    private int|float|string|null $min;

    /**
     * The maximum length of the content.
     * @var int|float|string|null
     */
    private int|float|string|null $max;

    /**
     * The step size between different values.
     * @var int|float|null
     */
    private int|float|null $step;

    /**
     * Set the input disabled or not.
     * @var bool
     */
    private bool $disabled = false;

    /**
     * Autocomplete.
     * @var bool
     */
    private bool $autoComplete = true;

    /**
     * An optional range list.
     * @var int[]|string[]|array|null
     */
    private array|null $legend = null;

    /**
     * Constructor.
     * @param string|null $id The id.
     * @param string $name The name.
     * @param string $type The type.
     * @param int|float|null|string $min The minimum (optional).
     * @param int|float|null|string $max The maximum (optional).
     * @param int|float|null $step The step size (optional).
     * @param string[]|string $classes Some classes.
     * @param string[]|null $legend An additional legend with possible values.
     */
    protected function __construct(?string $id, string $name, string $type,
                                   int|string|float|null $min = null, int|string|float|null $max = null,
                                   int|float|null $step = null,
                                   string|array $classes = [], array|null $legend = null) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'input' ], $classes));
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->legend = $legend;

        if ($type !== 'checkbox') $this->js('$("' . $this->idc() . '").on("paste", clearPaste);');

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.input', [], 'box-sizing: border-box', 'padding: 5px',
            'height: 50px', 'width: 100%', 'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));
        self::addStyle(WebshipTools::css('.input', ['[type="checkbox"]'], 'height: 25px'));
        self::addStyle(WebshipTools::css('.input', ['.fit[type="checkbox"]'], 'width: 25px'));
    }

    /**
     * Create a general input field.
     * @param string $id The id.
     * @param string $type The type.
     * @param int|float|null|string $min The minimum (optional).
     * @param int|float|null|string $max The maximum (optional).
     * @param int|float|null $step The step size (optional).
     * @param string[]|string $classes Some classes.
     * @param string[]|null $legend An additional legend.
     * @param string|null $name An optional name.
     * @return InputHtmlView
     */
    public static function createIt(string $id, string $type, int|string|float|null $min = null,
                                    int|string|float|null $max = null, int|float|null $step = null,
                                    string|array $classes = [], array|null $legend = null,
                                    string|null $name = null) : InputHtmlView {
        return new InputHtmlView($id, is_null($name) ? $id : $name, $type, $min, $max, $step, $classes, $legend);
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<input class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->id ? ' id="' . $this->id . '"' : '') . ' name="' . $this->name .
            '" type="' . $this->type . '"' .
            ($this->type === 'number' || $this->type === 'range' || $this->type === 'date' && $this->min ? ' min="' . $this->min . '"' : '') .
            ($this->type === 'number' || $this->type === 'range' || $this->type === 'date' && $this->max ? ' max="' . $this->max . '"' : '') .
            ($this->type === 'text'   && $this->max ? ' maxlength="' . $this->max . '"' : '') .
            (($this->type === 'number' || $this->type === 'range' || $this->type === 'time') && $this->step ? ' step="' . $this->step . '"' : '') .
            ($this->type === 'range' && !is_null($this->legend) ? ' list="' . $this->name . '-legend' . '"' : '') .
            (array_key_exists(View::$language, $this->hints) ?
                ' placeholder="' . implode(' ' ,$this->hints[View::$language]) . '"' :
                '') .
            ($this->disabled ? ' disabled ' : '') .
            (!$this->autoComplete ? ' autocomplete="off"' : '') .
            ($this->notices ? ' title="' . (array_key_exists(View::getLanguage(), $this->notices) ?
                    $this->notices[View::getLanguage()] : array_pop($this->notices)) . '"' : '') .
            ($this->data ? ' ' . implode(' ', array_map(function($key, $val) {
                    return 'data-' . $key . ($val !== null ? '="' . $val . '"' : '');
                }, array_keys($this->data), $this->data)) : '') .
            ($this->localStyles ? ' style="' . implode(';', $this->localStyles) . '"' : '') .
            ($this->type !== 'checkbox' ? ' value="' : '') .
            ($this->type === 'checkbox' && $this->value ? ' value="' . str_replace('"', "'", $this->value) . '"' : '');
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        $code = ($this->type !== 'checkbox' ? '">' : '>');
        if (!is_null($this->legend)) {
            $code .= '<datalist id="' . $this->name . '-legend' . '">' . implode(array_map(function ($l) {
                    return '<option label="' . $l . '">' . $l . '</option>';
                }, $this->legend)) . '</datalist>';
        }
        return $code;
    }

    /**
     * @inheritDoc
     */
    public function generate(bool $complete = false, bool $templates = false, bool $force = false) : string {
        $code = $this->generateOpeningTag();
        $code .= implode(array_map(function(View $view) { return $view->generateSafe(); }, $this->subviews));
        $code .= $this->generateClosingTag();
        if ($complete) {
            $code .= '<style type="text/css">' . $this->generateStyle() . '</style>' .
                '<script>' . $this->generateJS() . '</script>';
        }
        return $code;
    }

    /**
     * Get the value.
     * @return string|null
     */
    public function getValue() : ?string {
        return ($this->type === 'checkbox' ? $this->value : null);
    }

    /**
     * Shall it be forced to return the value?
     * @return bool
     */
    public function shallForce() : bool {
        return $this->force;
    }

    /**
     * Sets the value (for checkboxes) stored behind the checkbox.
     * @param string|IShippable|null|array|int|bool $value The value.
     * @return $this
     */
    public function val(string|IShippable|null|array|int|bool $value, bool $force = false) : InputHtmlView {
        if ($value instanceof IShippable ||
            (!is_bool($value) && !is_string($value) && !is_int($value) && !is_float($value) && !is_numeric($value) &&
                !is_null($value) && !is_array($value))) {
            $this->value = ShipperRegister::getObjectString($value);
        } else {
            if (is_array($value)) $value = WebshipTools::jsonEncode($value);
            $this->value = $value;
        }
        $this->force = $force;
        return $this;
    }

    /**
     * Get the type.
     * @return string
     */
    public function type() : string {
        return $this->type;
    }

    /**
     * Get the id.
     * @return string|null
     */
    public function getId() : ?string {
        return $this->id;
    }

    /**
     * Set disabled.
     * @param bool $disabled The new value.
     * @return $this
     */
    public function disabled(bool $disabled) : static {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * Set auto complete.
     * @param bool $autoComplete The autocomplete property.
     * @return $this
     */
    public function autoComplete(bool $autoComplete) : static {
        $this->autoComplete = $autoComplete;
        return $this;
    }
}
?>