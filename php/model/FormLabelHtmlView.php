<?php

/**
 * Class FormLabelHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class FormLabelHtmlView extends HtmlView {

    /**
     * The input field for which this label is for.
     * @var string|null
     */
    private ?string $for;

    /**
     * FormLabelHtmlView constructor.
     * @param string[]|string $classes Additional classes.
     */
    public function __construct(?string $for = null, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['form-label'], $classes));
        $this->for = $for;

        $this->style(Tools::css('label.form-label', '', 'display: block', 'width: 100%',
            'margin-top: 10px'));
    }

    /**
     * Generates the opening tag.
     * @return string
     */
    public function generateOpeningTag() : string {
        $id = $this->id();
        return '<label' . ($this->for ? ' for="' . $this->for . '"' : '') .
            ' class="' . implode(' ', array_merge($this->classes, array($id))) . '"' .
            ($id ? ' data-id="' . $id . '"' : '') . '>';
    }

    /**
     * Generates the closing tag.
     * @return string
     */
    public function generateClosingTag() : string {
        return '</label>';
    }
}
?>