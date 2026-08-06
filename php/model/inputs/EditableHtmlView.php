<?php

namespace webship\inputs;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View EditableHtmlView.
 *
 * <p>A basic input text where the text can be edited.</p>
 *
 * <code>
 * EditableHtmlView::create()->texts('Hello World, edit me!');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/editable.png" />
 *
 * @name EditableHtmlView
 * @category inputs
 * @package webship
 * @subpackge php/models/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     EditableHtmlView::create()->texts('Hello World, edit me!');
 */
class EditableHtmlView extends HtmlView {

    /**
     * Whether the editable html is enabled, or not.
     * @var bool
     */
    private bool $disabled = false;

    /**
     * Constructor.
     * @param string[]|string $classes (optional) Possible classes
     */
    private function __construct(array|string $classes = []) {
        parent::__construct(array_merge([ 'editable' ], is_array($classes) ? $classes : [ $classes ]));

        $this->ref('edit-field');
        $this->js('$("' . $this->idc() . '").on("paste", clearPaste);');
        $this->js('$("' . $this->idc() . '").on("keypress keyup", resizeHeight);');
        $this->js('resizeHeight($("' . $this->idc() . '")[0]);');

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('textarea.editable', [], 'border: none', 'overflow: auto',
            'outline: none', '-webkit-box-shadow: none', '-moz-box-shadow: none', 'box-shadow: none', 'resize: none',
            'font-size: 12px', 'font-family: sans-serif', 'padding: 0', 'background: none', 'overflow-y: hidden'));
    }

    /**
     * Create an editable text.
     * @param string[]|string $classes Some classes.
     * @return EditableHtmlView
     */
    public static function create(array|string $classes = []) : EditableHtmlView {
        return new EditableHtmlView($classes);
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
    public function disabled(bool $disabled) : static {
        $this->disabled = $disabled;
        return $this;
    }
}
?>