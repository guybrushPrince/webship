<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class FormHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class FormHtmlView extends HtmlView {

    /**
     * The action to perform.
     * @var string
     */
    private $action = "";

    /**
     * The method.
     * @var string
     */
    private $method = "";

    /**
     * FormHtmlView constructor.
     * @param string $action The action to perform.
     * @param string $method The method.
     * @param array $classes
     */
    public function __construct($action, $method, array $classes = array()) {
        parent::__construct(array_merge(array('form'), $classes));
        $this->action = $action;
        $this->method = $method;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: inherit', 'flex-wrap: inherit', 'margin: inherit'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<form action="' . $this->action . '" method="' . $this->method . '" class="' .
            implode(' ', array_merge($this->classes, array($this->id()))) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</form>';
    }



    // Short cuts

    /**
     * Return a new form html view.
     * @param string $action
     * @param string $method
     * @param array $classes Classes.
     * @return FormHtmlView
     */
    public static function _new($action, $method, array $classes) {
        return new FormHtmlView($action, $method, $classes);
    }

}
?>