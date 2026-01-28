<?php

/**
 * View CheckboxHtmlView.
 *
 * <p> A CheckboxHtmlView for checkable <em> checkboxes</em>.</p
 *
 *  <code>
 *  CheckboxHtmlView::create($id . 'checkbox1', true); // adds a checked checkbox with the id "checkbox1"
 *  </code>
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 * @view
 */
class CheckboxHtmlView extends InputHtmlView {

    /**
     * Constructor.
     * @param string|null $id The id.
     * @param bool $checked Whether the box is checked, or not.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(?string $id = null, bool $checked = false, array|string $classes = []) {
        parent::__construct($id, $id, 'checkbox', null, null, null, $classes);
        $this->texts()->text(View::getLanguage(), (!$checked ? '' : 'checked'));
        $this->addClass('fit');
    }
    
    /**
     * Create a new checkbox.
     * @param string|null $id The id.
     * @param bool $checked Whether the box is checked, or not.
     * @param string[]|string $classes Some classes.
     * @return CheckboxHtmlView
     */
    public static function create(?string $id = null, bool $checked = false,
                                  array|string $classes = []) : CheckboxHtmlView {
        return new CheckboxHtmlView($id, $checked, $classes);
    }

}
?>