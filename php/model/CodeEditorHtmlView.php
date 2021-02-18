<?php
/**
 * Class CodeEditorHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class CodeEditorHtmlView extends TextHtmlView {

    /**
     * The core classes.
     * @var array
     */
    private array $coreClasses;

    /**
     * CodeEditorHtmlView constructor.
     * @param string $id The id.
     * @param array $classes The classes.
     */
    public function __construct(string $id, array $classes = array()) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'code-editor';
        $this->coreClasses = $classes;
        parent::__construct($id, $id, 3, false, $classes, true);

        $this->style(Tools::css('.code-editor', [], 'width: 100%'));

        $this->style(Tools::css('.code-editor', [' > ', 'textarea'], 'width: 100%',
            'font-family: Consolas, "Courier New", monospace', 'font-weight: normal', 'font-size: 14px',
            'font-feature-settings: "liga" 0, "calt" 0', 'line-height: 19px', 'letter-spacing: 0px', 'height: 190px',
            'border: none', 'margin: none', 'background: rgba(255, 255, 230, 1.0)', 'padding: 5px 5px',
            'box-sizing: content-box'));

        $this->style(Tools::css('.code-editor', [' > ', 'textarea.text-input.resizable'],
            'height: inherit', 'overflow-y: auto'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<div class="' . implode(' ', $this->coreClasses) . '">' .
            '<div class="line-numbers"></div>' .
            parent::generateOpeningTag();
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return parent::generateClosingTag() . '</div>';
    }
}
?>