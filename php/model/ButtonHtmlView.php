<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ButtonHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ButtonHtmlView extends HtmlView {

    /**
     * An id.
     * @var string|false
     */
    private $id;

    /**
     * The type
     * @var string
     */
    private $type;

    /**
     * ButtonHtmlView constructor.
     * @param string|false $id The id.
     * @param string $type The type.
     * @param array|string $classes
     */
    public function __construct($id, $type, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('button'), $classes));
        $this->id = $id;
        $this->type = $type;

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
        return '<button class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->id ? ' id="' . $this->id . '"' : '') . ' type="' . $this->type . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</button>';
    }

    // Short cuts

}
?>