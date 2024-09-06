<?php

/**
 * Class CheckboxHtmlView.
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
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
        $this->texts()->text(View::getLanguage(), ($checked ? '' : 'checked'));
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