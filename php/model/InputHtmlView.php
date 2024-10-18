<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class InputHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
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
     * The minimum length of the content.
     * @var int|string|null
     */
    private int|string|null $min;

    /**
     * The maximum length of the content.
     * @var int|string|null
     */
    private int|string|null $max;

    /**
     * The step size between different values.
     * @var int|null
     */
    private ?int $step;

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
     * InputHtmlView constructor.
     * @param string|null $id The id.
     * @param string $name The name.
     * @param string $type The type.
     * @param int|null|string $min The minimum (optional).
     * @param int|null|string $max The maximum (optional).
     * @param int|null $step The step size (optional).
     * @param string[]|string $classes
     */
    public function __construct(?string $id, string $name, string $type,
                                int|string|null $min = null, int|string|null $max = null, ?int $step = null,
                                string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'input' ], $classes));
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'box-sizing: border-box', 'padding: 5px',
            'height: 50px', 'width: 100%', 'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));
        $this->style(Tools::css($coreSelector, '[type="checkbox"]', 'height: 25px'));
        if ($type !== 'checkbox') $this->js('$("' . $this->idc() . '").on("paste", clearPaste);');
        $this->style(Tools::css($coreSelector, '.fit[type="checkbox"]', 'width: 25px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<input class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->id ? ' id="' . $this->id . '"' : '') . ' name="' . $this->name .
            '" type="' . $this->type . '"' .
            ($this->type === 'number' || $this->type === 'date' && $this->min ? ' min="' . $this->min . '"' : '') .
            ($this->type === 'number' || $this->type === 'date' && $this->max ? ' max="' . $this->max . '"' : '') .
            ($this->type === 'text'   && $this->max ? ' maxlength="' . $this->max . '"' : '') .
            (($this->type === 'number' || $this->type === 'time') && $this->step ? ' step="' . $this->step . '"' : '') .
            (array_key_exists(View::$language, $this->hints) ?
                ' placeholder="' . implode(' ' ,$this->hints[View::$language]) . '"' :
                '') .
            ($this->disabled ? ' disabled ' : '') .
            (!$this->autoComplete ? ' autocomplete="off"' : '') .
            ($this->notices ? ' title="' . (array_key_exists(View::getLanguage(), $this->notices) ? $this->notices[View::getLanguage()] : array_pop($this->notices)) . '"' : '') .
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
    public function generateClosingTag() {
        return ($this->type !== 'checkbox' ? '">' : '>');
    }

    /**
     * @inheritDoc
     */
    public function generate($complete = false, $templates = false, $force = false) : string {
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
     * Sets the value (for checkboxes) stored behind the checkbox.
     * @param string|CliffSerializable|null|array|int|bool $value The value.
     * @return $this
     */
    public function val(string|CliffSerializable|null|array|int|bool $value) : InputHtmlView {
        if ($value instanceof CliffSerializable ||
            (!isbool($value) && !is_string($value) && !is_int($value) && !is_float($value) && !is_numeric($value) &&
                !is_null($value) && !is_array($value))) {
            $key = $value->getKey();
            $val = (is_integer($key) ? ActionRegister::convertKey($key) : $key);
            $class = get_class($value);
            $this->value = $class . '<::>' . $val;
        } else {
            if (is_array($value)) $value = Tools::jsonEncode($value);
            $this->value = $value;
        }
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
    public function disabled(bool $disabled) : InputHtmlView {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * Set auto complete.
     * @param bool $autoComplete The autocomplete property.
     * @return $this
     */
    public function autoComplete(bool $autoComplete) : InputHtmlView {
        $this->autoComplete = $autoComplete;
        return $this;
    }
}
?>