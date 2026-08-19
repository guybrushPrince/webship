<?php

namespace webship\inputs;

use webship\WebshipTools;

/**
 * View LabelledInputHtmlView.
 *
 * <p>Input fields usually have labels but being separated in HTML. This view combines an input field with a
 * possible label.</p>
 * <p>Different possibilities exist for arranging the labels with the corresponding input.</p>
 *
 * <code>
 * LabelledInputHtmlView::createTextField('Name', 'input-1');
 * LabelledInputHtmlView::createMiniField('Name', 'input-1');
 * LabelledInputHtmlView::createPasswordField('Password', 'input-3');
 * LabelledInputHtmlView::createCheckbox('Set?', 'input-4');
 * LabelledInputHtmlView::createMiniCheckbox('Set?', 'input-5');
 * LabelledInputHtmlView::createNumericField('Number', 'input-6');
 * LabelledInputHtmlView::createMiniNumericField('Number', 'input-7');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/labelledinput.png" />
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     LabelledInputHtmlView::createTextField('Name', 'input-1');
 *     LabelledInputHtmlView::createMiniField('Name', 'input-1');
 *     LabelledInputHtmlView::createPasswordField('Password', 'input-3');
 *     LabelledInputHtmlView::createCheckbox('Set?', 'input-4');
 *     LabelledInputHtmlView::createMiniCheckbox('Set?', 'input-5');
 *     LabelledInputHtmlView::createNumericField('Number', 'input-6');
 *     LabelledInputHtmlView::createMiniNumericField('Number', 'input-7');
 */
class LabelledInputHtmlView extends InputHtmlView {

    private const mainClass = 'linput';
    private const miniClass = 'mini';
    private const outsideClass = 'out';
    private const labelLessClass = 'll';
    private const bufferLessClass = 'bl';

    /**
     * Whether the input is mini or not.
     * @var bool
     */
    private bool $mini = false;

    /**
     * Whether the label can be presented inside the input.
     * @var bool
     */
    private bool $inside = true;

    /**
     * Whether the view is without labels, or not.
     * @var bool
     */
    private bool $labelLess = false;

    /**
     * Whether the view is without buffer, or not.
     * @var bool
     */
    private bool $bufferLess = false;

    /**
     * The labels.
     * @var string[]
     */
    private array $labels;

    /**
     * Constructor.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string $type The type (either *text* or *password*).
     * @param int|float|string|null $min The minimum length.
     * @param int|float|string|null $max The maximum length.
     * @param int|float|null $step The step size.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string|array|null $labels, string $id, string $type,
                                 int|string|float|null $min = null, int|string|float|null $max = null,
                                 int|float|null $step = null,
                                 array|string $classes = [], array|null $legend = null) {
        parent::__construct($id, $id, $type, $min, $max, $step, $classes, $legend);
        if ($labels === null) $labels = '';
        if ($labels === '') $this->labelLess = true;
        $this->labels = is_array($labels) ? $labels : [ self::getLanguage() => $labels ];
        $this->addHint(self::getLanguage(), '');

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.linput', [], 'position: relative', 'width: fit-content',
            'box-sizing: border-box', 'width: -moz-fit-content', '--ws-li-height: 30px', '--ws-li-buffer: 10px',
            'height: calc(var(--ws-li-height) + var(--ws-li-buffer))', '--ws-li-in-width: 200px',
            'display: flex', 'flex-direction: row', 'align-items: flex-end', 'flex-wrap: nowrap'));
        self::addStyle(WebshipTools::css('.linput.bl', [], '--ws-li-buffer: 0'));
        self::addStyle(WebshipTools::css('.linput.ll', [], '--ws-li-buffer: 0'));
        self::addStyle(WebshipTools::css('.linput.ll', [' > ', 'input + label'], 'display: none'));
        self::addStyle(WebshipTools::css('.linput', [' > ', 'input'], 'height: var(--ws-li-height)',
            'box-sizing: border-box', 'width: var(--ws-li-in-width)'));
        self::addStyle(WebshipTools::css('.linput', [' > ', 'input:not([type=date]):not([type=checkbox]) + label'],
            'position: absolute', 'box-sizing: border-box', 'left: 0', 'color: var(--ws-std-bg-dark)',
            'transition: 0.5s'));
        self::addStyle(WebshipTools::css('.linput', [' > ', 'input:not(:focus):not([type=date]):not([type=checkbox]):placeholder-shown + label'],
            'display: flex', 'top: var(--ws-li-buffer)', 'flex-direction: row',
            'height: calc(100% - var(--ws-li-buffer))', 'align-items: center', 'cursor: text', 'padding-left: 8px',
            'font-size: 13px'));
        self::addStyle(WebshipTools::css('.linput', [' > ', 'input:focus:not([type=date]):not([type=checkbox]) + label'],
            'bottom: calc(100% - var(--ws-li-buffer))', 'font-size: 10px', 'top: unset'));
        self::addStyle(WebshipTools::css('.linput', [' > ', 'input:not([type=date]):not([type=checkbox]):not(:placeholder-shown) + label'],
            'bottom: calc(100% - var(--ws-li-buffer))', 'font-size: 10px', 'top: unset'));
        self::addStyle(WebshipTools::css('.linput', [' > ', 'input[type=checkbox]'], '--ws-li-height: 25px',
            '--ws-li-margin: 2.5px', 'margin: var(--ws-li-margin)',
            'height: var(--ws-li-height)', 'width: var(--ws-li-height)', 'box-sizing: border-box'));
        self::addStyle(WebshipTools::css('.linput', [' > ', 'input[type=checkbox] + label'], 'cursor: pointer'));

        self::addStyle(WebshipTools::css('.linput.mini', [], '--ws-li-height: 20px', '--ws-li-buffer: 0'));
        self::addStyle(WebshipTools::css('.linput.mini', [' > ', 'input'], 'border-radius: 3px',
            'font-size: 10px', 'border: 1px solid var(--ws-font-info)', 'color: var(--ws-font-info)'));
        self::addStyle(WebshipTools::css('.linput.mini', [' > ', 'input:focus'], 'outline: none'));
        self::addStyle(WebshipTools::css('.linput.mini', [' > ', 'input:not([type=date]):not([type=checkbox]):placeholder-shown + label'],
            'height: var(--ws-li-height)', 'color: var(--ws-font-info)', 'font-size: 10px', 'padding-left: 5px'));
        self::addStyle(WebshipTools::css('.linput.mini', [' > ', 'input:focus:not([type=date]):not([type=checkbox]):placeholder-shown + label'],
            'display: none'));
        self::addStyle(WebshipTools::css('.linput.mini', [' > ', 'input:not([type=date]):not([type=checkbox]):not(:placeholder-shown) + label'],
            'display: none'));
        self::addStyle(WebshipTools::css('.linput.mini', [' > ', 'input[type=checkbox]'],'--ws-li-height: 15px'));

        self::addStyle(WebshipTools::css('.linput.out', [], 'flex-direction: row-reverse'));
        self::addStyle(WebshipTools::css('.linput.out', [' > ', 'label'], 'display: flex', 'flex-wrap: nowrap',
            'flex-direction: row', 'height: var(--ws-li-height)', 'width: fit-content', 'width: -moz-fit-content',
            'align-items: center'));
        self::addStyle(WebshipTools::css('.linput.out', [' > ', 'label:not(:empty):after'],
            'content: ":"', 'display: block', 'margin-right: 5px'));
        self::addStyle(WebshipTools::css('.linput.mini.out', [' > ', 'label'], 'color: var(--ws-font-info)',
            'font-size: 10px'));
        self::addStyle(WebshipTools::css('.linput.mini.out', [' > ', 'label:empty'], 'display: none'));
        self::addStyle(WebshipTools::css('.linput.mini.out', [' > ', 'label:not(:empty):after'],
            'color: var(--ws-font-info)', 'font-size: 10px', 'content: ":"', 'display: block',
            'margin-right: 5px'));
        self::addStyle(WebshipTools::css('.linput', [' > ', 'input.long'], '--ws-li-in-width: 400px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<div class="' . self::mainClass . ($this->mini ? ' ' . self::miniClass : '') .
            ($this->labelLess ? ' ' . self::labelLessClass : '') .
            ($this->bufferLess ? ' ' . self::bufferLessClass : '') .
            (!$this->inside ? ' ' . self::outsideClass : '') . '">' .
            parent::generateOpeningTag();
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return parent::generateClosingTag() .
            '<label for="' . $this->getId() . '" onclick="$(\'#' . $this->getId() . '[type=text]\').focus();">' .
            ($this->mini && $this->inside ? '[' : '') .
            str_replace(' ', '&nbsp;', $this->labels[self::getLanguage()]) .
            ($this->mini && $this->inside ? ']' : '') .
            '</label></div>';
    }

    /**
     * Create a text input field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param string|null $value The start value.
     * @param int|null $min The minimum length.
     * @param int|null $max The maximum length.
     * @return LabelledInputHtmlView
     */
    public static function createTextField(string|array|null $labels, string $id, array|string $classes = [],
                                           ?string $value = null, ?int $min = null,
                                           ?int $max = null) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'text', $min, $max, null, $classes);
        if ($value) $input->texts($value);
        return $input;
    }

    /**
     * Create a numeric input field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param int|float|null $value The start value.
     * @param int|float|null $min The minimum value.
     * @param int|float|null $max The maximum value.
     * @param int|float|null $step The step size.
     * @return LabelledInputHtmlView
     */
    public static function createNumericField(string|array|null $labels, string $id, array|string $classes = [],
                                              int|float|null $value = null,
                                              int|float|null $min = null, int|float|null $max = null,
                                              int|float|null $step = null) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'number', $min, $max, $step, $classes);
        if ($value || is_int($value) || is_float($value)) $input->texts()->text(self::getLanguage(), $value);
        return $input;
    }

    /**
     * Create a numeric range input field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param int|float|null $value The start value.
     * @param int|float|null $min The minimum value.
     * @param int|float|null $max The maximum value.
     * @param int|float|null $step The step size.
     * @param array|null $legend A list of legend (x-axis) values.
     * @return LabelledInputHtmlView
     */
    public static function createRangeField(string|array|null $labels, string $id, array|string $classes = [],
                                            int|float|null $value = null,
                                            int|float|null $min = null, int|float|null $max = null,
                                            int|float|null $step = null,
                                            array|null $legend = null) : LabelledInputHtmlView {
        if (is_null($legend) && !is_null($min) && !is_null($max)) {
            $legend = [
                $min,
                ($max - $min) / 2,
                $max
            ];
        }
        $input = new LabelledInputHtmlView($labels, $id, 'range', $min, $max, $step, $classes, $legend);
        if ($value || is_int($value) || is_float($value)) $input->texts($value);
        return $input;
    }

    /**
     * Creates a mini text field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param string|null $value The start value.
     * @param int|null $min The minimum length.
     * @param int|null $max The maximum length.
     * @return LabelledInputHtmlView
     */
    public static function createMiniField(string|array|null $labels, string $id, array|string $classes = [],
                                           ?string $value = null,
                                           ?int $min = null, ?int $max = null) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'text', $min, $max, null, $classes);
        if ($value) $input->texts()->text(self::getLanguage(), $value);
        $input->mini = true;
        return $input;
    }

    /**
     * Create a mini numeric input field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param int|float|null $value The start value.
     * @param int|float|null $min The minimum value.
     * @param int|float|null $max The maximum value.
     * @param int|float|null $step The step size.
     * @return LabelledInputHtmlView
     */
    public static function createMiniNumericField(string|array|null $labels, string $id, array|string $classes = [],
                                                  int|float|null $value = null,
                                                  int|float|null $min = null, int|float|null $max = null,
                                                  int|float|null $step = null) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'number', $min, $max, $step, $classes);
        if ($value || is_int($value) || is_float($value)) $input->texts()->text(self::getLanguage(), $value);
        $input->mini = true;
        return $input;
    }

    /**
     * Create a password input field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param string|null $value The initial value.
     * @param int|null $min The minimum length.
     * @param int|null $max The maximum length.
     * @return LabelledInputHtmlView
     */
    public static function createPasswordField(string|array|null $labels, string $id, array|string $classes = [],
                                               string|null $value = null,
                                               int|null $min = null, int|null $max = null) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'password', $min, $max, null, $classes);
        if ($value) $input->texts($value);
        return $input;
    }

    /**
     * Create a date field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string|int|null $value The (optional) value.
     * @param string|null $minDate The minimum date.
     * @param int|null $step The step size (distance).
     * @param string[]|string $classes Some classes.
     * @return LabelledInputHtmlView
     */
    public static function createDateField(string|array|null $labels, string $id, string|int|null $value = null,
                                           string|null $minDate = null,
                                           int|null $step = null, array|string $classes = []) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'date', $minDate, null, $step, $classes);
        if ($value !== null) $input->texts()->text(self::getLanguage(), $value);
        $input->inside = false;
        return $input;
    }

    /**
     * Create a time field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string|int|null $value The (optional) value.
     * @param int|null $step The step size.
     * @param string[]|string $classes Some classes.
     * @return LabelledInputHtmlView
     */
    public static function createTimeField(string|array|null $labels, string $id, string|int|null $value = null,
                                           int|null $step = null, array|string $classes = []) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'time', null, null, $step, $classes);
        if ($value !== null) $input->texts()->text(self::getLanguage(), $value);
        $input->inside = false;
        return $input;
    }

    /**
     * Create a mini date field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string|int|null $value The (optional) value.
     * @param int|null $step The step size.
     * @param string[]|string $classes Some classes.
     * @return LabelledInputHtmlView
     */
    public static function createMiniDateField(string|array|null $labels, string $id, string|int|null $value = null,
                                               int|null $step = null,
                                               array|string $classes = []) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'date', null, null, $step, $classes);
        if ($value !== null) $input->texts($value);
        $input->mini = true;
        $input->inside = false;
        return $input;
    }

    /**
     * Create a checkbox.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param bool|null $checked Whether the checkbox is checked, or not.
     * @param string[]|string $classes Some classes.
     * @return LabelledInputHtmlView
     */
    public static function createCheckbox(string|array|null $labels, string $id, ?bool $checked = false,
                                          array|string $classes = []) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'checkbox', null, null, null, $classes);
        $input->texts((!$checked ? '' : 'checked'));
        if ($labels) {
            if (!is_array($labels)) $labels = [ self::getLanguage() => $labels ];
            foreach ($labels as $lang => $label) $input->notice($lang, $label);
        }
        $input->inside = false;
        $input->bufferLess = true;
        return $input;
    }

    /**
     * Create a mini checkbox.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param bool|null $checked Whether the checkbox is checked, or not.
     * @param string[]|string $classes Some classes.
     * @return LabelledInputHtmlView
     */
    public static function createMiniCheckbox(string|array|null $labels, string $id, ?bool $checked = false,
                                              array|string $classes = []) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'checkbox', null, null, null, $classes);
        $input->texts((!$checked ? '' : 'checked'));
        if (!is_null($labels)) {
            if (!is_array($labels)) $labels = [ self::getLanguage() => $labels ];
            foreach ($labels as $lang => $label) $input->notice($lang, $label);
        }
        $input->mini = true;
        $input->inside = false;
        return $input;
    }

    /**
     * Create a mini radio.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param bool|null $selected Whether the checkbox is checked, or not.
     * @param string[]|string $classes Some classes.
     * @return LabelledInputHtmlView
     */
    public static function createMiniRadio(string|array|null $labels, string $id, ?bool $selected = false,
                                           array|string $classes = []) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'radio', null, null, null, $classes);
        $input->texts((!$selected ? '' : 'checked'));
        if (!is_array($labels)) $labels = [ self::getLanguage() => $labels ];
        foreach ($labels as $lang => $label) $input->notice($lang, $label);
        $input->mini = true;
        $input->inside = false;
        return $input;
    }

    /**
     * Set a longer input field.
     * @return $this
     */
    public function long() : static {
        $this->addClass('long');
        return $this;
    }
}
?>