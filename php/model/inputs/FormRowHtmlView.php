<?php

namespace webship\inputs;

use ContainerHtmlView;
use Tools;

/**
 * View FormRowHtmlView.
 *
 * <p>Allows for placing multiple inputs in one row and vertical align at bottom. This aligned is also applied to
 * nested {@link \KeyValueHtmlView} views.</p>
 *
 * <code>
 * FormRowHtmlView::create();
 * </code>
 *
 * No figure is provided.
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     FormRowHtmlView::create();
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

    /**
     * Create a form row.
     * @param string[]|string $classes Some classes.
     * @return FormRowHtmlView
     */
    public static function create(array|string $classes = []) : FormRowHtmlView {
        return new FormRowHtmlView($classes);
    }
}
?>