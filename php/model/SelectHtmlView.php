<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class SelectHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class SelectHtmlView extends HtmlView {

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
     * Set the button disabled or not.
     * @var bool
     */
    private bool $disabled = false;

    /**
     * Whether the select is hidden or not.
     * @var bool
     */
    private bool $hidden;

    /**
     * The opening tag.
     * @var string
     */
    private string $opening = 'select';

    /**
     * SelectHtmlView constructor.
     * @param string $id The id.
     * @param string $name The name.
     * @param array|string $classes
     */
    public function __construct($id, $name, $classes = array(), bool $hidden = false) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('select'), $classes));
        $this->id = $id;
        $this->name = $name;
        $this->hidden = $hidden;

        $this->option('-1')->texts()->
            text(Language::ENGLISH, 'Please select')->
            text(Language::GERMAN,  'Bitte auswählen');

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'box-sizing: border-box', 'padding: 5px',
            'height: 50px', 'width: 100%', 'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        if (count($this->subviews) === 1) {
            $this->subviews = array();
            $this->texts()->
                text(Language::ENGLISH, 'No options available')->
                text(Language::GERMAN,  'Keine Auswahl verfügbar');
            $this->opening = 'div';
            return '<div class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">';
        } else  {
            if (count($this->subviews) === 2) {
                $this->subviews = array($this->subviews[1]);
                $this->classes[] = "singleton";
            }
            return '<select class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '" id="' . $this->id .
                '" name="' . $this->name . '"' . ($this->disabled ? ' disabled' : '') . ($this->hidden ? ' hidden' : '') . '>';
        }
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</' . $this->opening . '>';
    }

    // Short cuts

    /**
     * Add an option.
     * @param string|CObject|BObject $value The value.
     * @param array|string $classes Classes.
     * @param boolean $selected Whether it is selected.
     * @return OptionHtmlView
     */
    public function option($value, $classes = array(), $selected = false) : OptionHtmlView {
        $option = new OptionHtmlView($value, $classes, $selected);
        $this->add($option);
        return $option;
    }

    /**
     * Set disabled.
     * @param bool $disabled The new value.
     * @return $this
     */
    public function disabled(bool $disabled) : SelectHtmlView {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * Get the id.
     * @return string|null
     */
    public function getId() : ?string {
        return $this->id;
    }
}
?>