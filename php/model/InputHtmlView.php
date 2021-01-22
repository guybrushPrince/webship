<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class InputHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class InputHtmlView extends HtmlView {

    /**
     * An id.
     * @var string|false
     */
    private $id;

    /**
     * The name
     * @var string
     */
    private $name;

    /**
     * The type
     * @var string
     */
    private $type;

    /**
     * The min
     * @var boolean|integer
     */
    private $min;

    /**
     * The max
     * @var boolean|integer
     */
    private $max;

    /**
     * The step
     * @var boolean|integer
     */
    private $step;

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
     * @param string|false $id The id.
     * @param string $name The name.
     * @param string $type The type.
     * @param integer|boolean $min The minimum.
     * @param integer|boolean $max The maximum.
     * @param integer|boolean $step The step.
     * @param array|string $classes
     */
    public function __construct($id, $name, $type, $min = false, $max = false, $step = false, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('input'), $classes));
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
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<input class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->id ? ' id="' . $this->id . '"' : '') . ' name="' . $this->name .
            '" type="' . $this->type . '"' .
            ($this->type === 'number' && $this->min ? ' min="' . $this->min . '"' : '') .
            ($this->type === 'number' && $this->max ? ' max="' . $this->max . '"' : '') .
            ($this->type === 'text'   && $this->max ? ' maxlength="' . $this->max . '"' : '') .
            ($this->type === 'number' && $this->step ? ' step="' . $this->step . '"' : '') .
            (in_array(View::$language, $this->hints) ?
                ' placeholder="' . implode(' ',$this->hints[View::$language]) . '"' :
                '') .
            ($this->disabled ? ' disabled ' : '') .
            (!$this->autoComplete ? ' autocomplete="off"' : '') .
            ($this->type !== 'checkbox' ? ' value="' : '');
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
    public function generate($complete = false, $templates = false) {
        $code = $this->generateOpeningTag();
        $code .= implode(array_map(function(View $view) { return $view->generateSafe(); }, $this->subviews));
        $code .= $this->generateClosingTag();
        if ($complete) {
            $code .= '<style type="text/css">' . $this->generateStyle() . '</style>' .
                '<script>' . $this->generateJS() . '</script>';
        }
        return $code;
    }

    // Short cuts

    /**
     * Get the type.
     * @return string
     */
    public function type() {
        return $this->type;
    }

    /**
     * Set disabled.
     * @param bool $disabled The new value.
     * @return $this
     */
    public function disabled(bool $disabled): InputHtmlView {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * Set auto complete.
     * @param bool $autoComplete The autocomplete property.
     * @return $this
     */
    public function autoComplete(bool $autoComplete): InputHtmlView {
        $this->autoComplete = $autoComplete;
        return $this;
    }
}
?>