<?php

/**
 * Class EditableHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class EditableHtmlView extends HtmlView {

    /**
     * EditableHtmlView constructor.
     * @param array|string $classes (optional) Possible classes
     */
    public function __construct($classes = array()) {
        parent::__construct($classes);
    }

    /**
     * Generates the opening tag.
     * @return string
     */
    public function generateOpeningTag() : string {
        return '<div class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '" ' .
            'contentEditable="true">';
    }

}
?>