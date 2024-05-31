<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class TextHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TextHtmlView extends HtmlView {

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
     * @var integer
     */
    private $rows;

    /**
     * The max
     * @var boolean|integer
     */
    private $max;

    /**
     * Set the button disabled or not.
     * @var bool
     */
    private bool $disabled = false;

    /**
     * InputHtmlView constructor.
     * @param string|false $id The id.
     * @param string $name The name.
     * @param string $rows The rows.
     * @param integer|boolean $max The maximum.
     * @param array|string $classes
     */
    public function __construct($id, $name, $rows = 3, $max = false, $classes = array(), $resizable = false) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('text-input'), ($resizable ? array('resizable') : array()) , $classes));
        $this->id = $id;
        $this->name = $name;
        $this->rows = $rows;
        $this->max = $max;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'box-sizing: border-box', 'padding: 5px',
            'height: ' . ($rows * 50) . 'px', 'width: 100%', 'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));

        $this->style(Tools::css($coreSelector, '.resizable', 'height: 31px', 'overflow: hidden'));

        if ($resizable) {
            $this->protocol(Event::INPUT)->reheight($this);
        }
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $hints = $this->hints;
        $this->hints = [];
        return '<textarea class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->id ? 'id="' . $this->id . '"' : '') . ' name="' . $this->name .
            '" rows="' . $this->rows . '"' .
            ($this->max ? ' maxlength="' . $this->max . '"' : '') .
            (array_key_exists(View::$language, $hints) ?
                ' placeholder="' . implode(' ', $hints[View::$language]) . '"' :
                '') .
            ($this->disabled ? ' disabled' : '') .
            '>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</textarea>';
    }

    /**
     * Set/get disabled.
     * @param ?bool $disabled The new value.
     * @return $this|bool
     */
    public function disabled(?bool $disabled = null): TextHtmlView|bool {
        if ($disabled === null) return $this->disabled;
        $this->disabled = $disabled;
        return $this;
    }

}
?>