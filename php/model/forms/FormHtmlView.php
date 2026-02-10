<?php

namespace webship\forms;

use HtmlView;
use Tools;

/**
 * View FormHtmlView.
 *
 * <p>A HTML form view collecting several different input fields.</p>
 *
 * <code>
 * FormHtmlView::create();
 * (new HtmlView)->form();
 * </code>
 *
 * There is nothing to present.
 *
 * @package webship
 * @subpackge php/model/forms
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.3.0
 *
 * @view
 * @examples
 *     FormHtmlView::create();
 *     (new HtmlView)->form();
 */
class FormHtmlView extends HtmlView {

    /**
     * The action to perform.
     * @var string
     */
    private string $action = '';

    /**
     * The method.
     * @var string
     */
    private string $method = '';

    /**
     * Constructor.
     * @param string $action The action to perform.
     * @param string $method The method.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string $action = '', string $method = '', array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'form' ], $classes));
        $this->action = $action;
        $this->method = $method;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('form', [], 'display: inherit',
            'flex-wrap: inherit', 'margin: inherit', 'width: inherit', 'align-items: inherit',
            'flex-direction: inherit'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<form action="' . $this->action . '" method="' . $this->method . '" class="' .
            implode(' ', array_merge($this->classes, [ $this->id() ])) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</form>';
    }

    /**
     * Return a new form html view.
     * @param string $action The action to perform (GET, POST, etc.)
     * @param string $method The URL to request.
     * @param string[]|string $classes Some classes.
     * @return FormHtmlView
     * @deprecated
     */
    public static function _new(string $action = '', string $method = '', array|string $classes = []) : FormHtmlView {
        return new FormHtmlView($action, $method, $classes);
    }

    /**
     * Create a new form.
     * @param string[]|string $classes Some classes.
     * @param string $action The action to perform (GET, POST, etc.)
     * @param string $method The URL to request.
     * @return FormHtmlView
     */
    public static function create(array|string $classes = [], string $action = '', string $method = '') : FormHtmlView {
        return new FormHtmlView($action, $method, $classes);
    }
}
?>