<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ButtonHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ButtonHtmlView extends HtmlView {

    /**
     * An id.
     * @var string
     */
    private $id;

    /**
     * The type
     * @var string
     */
    private $type;

    /**
     * ButtonHtmlView constructor.
     * @param string $id The id.
     * @param string $type The type.
     * @param array $classes
     */
    public function __construct($id, $type, array $classes = array()) {
        parent::__construct(array_merge(array('button'), $classes));
        $this->id = $id;
        $this->type = $type;

        /*$coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'box-sizing: border-box', 'padding: 5px',
            'height: 50px', 'width: 100%', 'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));*/
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<button class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '" id="' . $this->id .
            '" type="' . $this->type . '">';
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