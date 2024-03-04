<?php
/**
 * Class CodeEditorHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class CodeEditorHtmlView extends TextHtmlView {

    /**
     * The core classes.
     * @var string[]
     */
    private array $coreClasses;

    /**
     * CodeEditorHtmlView constructor.
     * @param string $id The id.
     * @param int|null $language The programming language.
     * @param string|string[] $classes The classes.
     */
    public function __construct(string $id, ?int $language = null, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'code-editor';
        $this->coreClasses = $classes;
        parent::__construct($id, $id, 3, false, $classes, true);

        $this->style(Tools::css('.code-editor', [], 'width: 100%'));

        $this->style(Tools::css('.code-editor', [' > ', 'textarea'], 'width: 100%',
            'font-family: Consolas, "Courier New", monospace', 'font-weight: normal', 'font-size: 14px',
            'font-feature-settings: "liga" 0, "calt" 0', 'line-height: 19px', 'letter-spacing: 0px', 'height: 190px',
            'border: none', 'margin: none', 'background: var(--text-editor-background)', 'padding: 5px 5px',
            'box-sizing: content-box'));

        $this->style(Tools::css('.code-editor', [' > ', 'textarea.text-input.resizable'],
            'height: inherit', 'overflow-y: auto'));

        $this->style(Tools::css('.CodeMirror-hints', [], 'z-index: 100000000000', 'position: absolute'));

        $this->ref('textarea');

        $this->js('let cm' . $this->id() . ' = CodeMirror.fromTextArea($("' . $this->idc() . '")[0], { ' .
            ($language !== null ? 'mode: "' . [
                ProgrammingLanguage::CSS   => 'css',
                ProgrammingLanguage::R     => 'r',
                ProgrammingLanguage::HTML  => 'html',
                ProgrammingLanguage::PHP   => 'php',
                ProgrammingLanguage::JS    => 'javascript',
                ProgrammingLanguage::LATEX => 'latex',
                ProgrammingLanguage::CSV   => 'csv'
            ][(array_key_exists($language, ProgrammingLanguage::$PROGRAMMING_LANGUAGE) ?
                $language : ProgrammingLanguage::PHP)] . '",' : 'mode: "expression",') .
            'lineNumbers: true, lineWrapping: true, extraKeys: {"Ctrl-Space": "autocomplete"}, });');

        $this->js('$("' . $this->idc() . '").on("' . Event::CONTENTMODIFY . '", function(event) {' .
            'cm' . $this->id() . '.save();' .
            '});');
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