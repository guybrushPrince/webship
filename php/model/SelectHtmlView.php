<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class SelectHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class SelectHtmlView extends HtmlView {

    /**
     * An id.
     * @var string
     */
    private $id;

    /**
     * The name
     * @var string
     */
    private $name;

    /**
     * SelectHtmlView constructor.
     * @param string $id The id.
     * @param string $name The name.
     * @param array $classes
     */
    public function __construct($id, $name, array $classes = array()) {
        parent::__construct(array_merge(array('select'), $classes));
        $this->id = $id;
        $this->name = $name;

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
            return '';
        } else  {
            if (count($this->subviews) === 2) {
                $this->subviews = array($this->subviews[1]);
                $this->classes[] = "singleton";
            }
            return '<select class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '" id="' . $this->id .
                '" name="' . $this->name . '">';
        }
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        if (count($this->subviews) >= 1) return '</select>';
        else return '';
    }

    // Short cuts

    /**
     * Add an option.
     * @param string $value The value.
     * @param array $classes Classes.
     * @param boolean $selected Whether it is selected.
     * @return OptionHtmlView
     */
    public function option($value, array $classes = array(), $selected = false) {
        $option = new OptionHtmlView($value, $classes, $selected);
        $this->add($option);
        return $option;
    }
}
?>