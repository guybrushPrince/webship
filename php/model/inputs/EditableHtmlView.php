<?php

/**
 * Class EditableHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class EditableHtmlView extends HtmlView {

    /**
     * Whether the editable html is enabled, or not.
     * @var bool
     */
    private bool $disabled = false;

    /**
     * EditableHtmlView constructor.
     * @param array|string $classes (optional) Possible classes
     */
    public function __construct($classes = array()) {
        parent::__construct(array_merge(['editable'], is_array($classes) ? $classes : [ $classes ]));
        $this->style(Tools::css('textarea.editable', [], 'border: none', 'overflow: auto',
            'outline: none', '-webkit-box-shadow: none', '-moz-box-shadow: none', 'box-shadow: none', 'resize: none',
            'font-size: 12px', 'font-family: sans-serif', 'padding: 0', 'background: none', 'overflow-y: hidden'));
        $this->ref('edit-field');
        $this->js('$("' . $this->idc() . '").on("paste", clearPaste);');
        $this->js('$("' . $this->idc() . '").on("keypress keyup", resizeHeight);');
        $this->js('resizeHeight($("' . $this->idc() . '")[0]);');
    }

    /**
     * Generates the opening tag.
     * @return string
     */
    public function generateOpeningTag() : string {
        return '<textarea class="' . implode(' ', array_merge($this->classes, [$this->id()])) . '" ' .
            ($this->disabled ? 'disabled' : '') . '>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</textarea>';
    }

    /**
     * Set the view disabled.
     * @param bool $disabled Whether the view is enabled, or not.
     * @return $this
     */
    public function disabled(bool $disabled) : HtmlView {
        $this->disabled = $disabled;
        return $this;
    }
}
?>