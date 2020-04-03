<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class TextHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TextHtmlView extends HtmlView {

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
     * InputHtmlView constructor.
     * @param string $id The id.
     * @param string $name The name.
     * @param string $rows The rows.
     * @param integer|boolean $max The maximum.
     * @param array $classes
     */
    public function __construct($id, $name, $rows = 3, $max = false, array $classes = array()) {
        parent::__construct(array_merge(array('text-input'), $classes));
        $this->id = $id;
        $this->name = $name;
        $this->rows = $rows;
        $this->max = $max;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'box-sizing: border-box', 'padding: 5px',
            'height: ' . ($rows * 50) . 'px', 'width: 100%', 'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<textarea class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '" id="' . $this->id . '" name="' . $this->name .
            '" rows="' . $this->rows . '"' .
            ($this->max ? ' maxlength="' . $this->max . '"' : '') .
            (in_array(View::$language, $this->hints) ?
                ' placeholder="' . implode(' ',$this->hints[View::$language]) . '"' :
                '') .
            '>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</textarea>';
    }

    // Short cuts

}
?>