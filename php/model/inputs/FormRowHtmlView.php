<?php

/**
 * View FormRowHtmlView.
 *
 *  <p> A view to create new form rows </p>
 *
 *  <code>
 *  $view = new FormRowHtmlView::create();
 *  </code>
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 * @view
 */
class FormRowHtmlView extends ContainerHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes = array_merge($classes, ['hor', 'single', 'gap', 'vbot', 'form-row']);
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * Create a form row.
     * @param string[]|string $classes Some classes.
     * @return FormRowHtmlView
     */
    public static function create(array|string $classes = []) : FormRowHtmlView {
        return new FormRowHtmlView($classes);
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.form-row', [], 'height: 40px', 'box-sizing: border-box'));
        self::addStyle(Tools::css('.form-row', [' > ', '.kv'], 'padding: 0', 'height: 30px',
            'min-height: 30px', 'margin-top: 10px', 'width: fit-content', 'width: -moz-fit-content'));
        self::addStyle(Tools::css('.form-row', [' > ', '.kv', ' > ', '.k'],
            'justify-content: flex-end', 'width: fit-content', 'width: -moz-fit-content'));
        self::addStyle(Tools::css('.form-row', [' > ', '.kv', ' > ', '.v'],
            'justify-content: flex-start', 'width: fit-content', 'width: -moz-fit-content'));
    }

}
?>