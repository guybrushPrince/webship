<?php

namespace webship\inputs;

use webship\interoperability\WEvent;
use ship\interoperability\SProgrammingLanguage;
use webship\WebshipTools;

/**
 * View CodeEditorHtmlView.
 *
 * <p>An interactive code editor view for programming languages.</p>
 *
 * <code>
 * $editor = CodeEditorHtmlView::createIt('java', SProgrammingLanguage::JAVA);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/codeeditor.png" />
 * 
 * @title CodeEditor
 * @category inputs
 * 
 * @package webship
 * @subpackage php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $editor = CodeEditorHtmlView::createIt('java', SProgrammingLanguage::JAVA);
 */
class CodeEditorHtmlView extends TextHtmlView {

    /**
     * The core classes.
     * @var string[]
     */
    private array $coreClasses;

    /**
     * The programming language (as one of {@link SProgrammingLanguage}).
     * @var int|null
     */
    private ?int $programmingLanguage;

    /**
     * CodeEditorHtmlView constructor.
     * @param string $id The id.
     * @param int|null $language The programming language.
     * @param string[]|string $classes The classes.
     */
    public function __construct(string $id, ?int $language = null, array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'code-editor';
        $this->coreClasses = $classes;
        parent::__construct($id, $id, 3, null, $classes, true);

        $this->ref('textarea');

        $this->programmingLanguage = $language;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.code-editor', [], 'width: 100%'));
        self::addStyle(WebshipTools::css('.code-editor', ['.fit'], 'height: fit-content',
            'height: -moz-fit-content'));
        self::addStyle(WebshipTools::css('.code-editor', ['.fit', ' > ', '.CodeMirror'], 'height: 100%'));

        self::addStyle(WebshipTools::css('.code-editor', [' > ', 'textarea'], 'width: 100%',
            'font-family: Consolas, "Courier New", monospace', 'font-weight: normal', 'font-size: 14px',
            'font-feature-settings: "liga" 0, "calt" 0', 'line-height: 19px', 'letter-spacing: 0px', 'height: 190px',
            'border: none', 'margin: none', 'background: var(--ws-code-editor-bg)', 'padding: 5px 5px',
            'box-sizing: content-box'));

        self::addStyle(WebshipTools::css('.code-editor', [' > ', 'textarea.text-input.resizable'],
            'height: inherit', 'overflow-y: auto'));

        self::addStyle(WebshipTools::css('.CodeMirror-hints', [], 'z-index: 100000000000', 'position: absolute'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        array_unshift($this->javaScripts,
            'let cm' . $this->id() . ' = CodeMirror.fromTextArea($("' . $this->idc() . '")[0], { ' .
            ($this->programmingLanguage !== null ? 'mode: "' . [
                    SProgrammingLanguage::CSS => 'css',
                    SProgrammingLanguage::R => 'r',
                    SProgrammingLanguage::HTML => 'html',
                    SProgrammingLanguage::PHP => 'php',
                    SProgrammingLanguage::JS => 'javascript',
                    SProgrammingLanguage::LATEX => 'latex',
                    SProgrammingLanguage::CSV => 'csv',
                    SProgrammingLanguage::PYTHON => 'python',
                    SProgrammingLanguage::VERIFIER => 'php'
                ][(array_key_exists($this->programmingLanguage, SProgrammingLanguage::PROGRAMMING_LANGUAGE) ?
                    $this->programmingLanguage : SProgrammingLanguage::PHP)] . '",' : 'mode: "expression",') .
            'lineNumbers: true, lineWrapping: true, extraKeys: {"Ctrl-Space": "autocomplete"}, ' .
            ($this->disabled() ? 'readOnly: true' : '') .
            '});');

        array_unshift($this->javaScripts,
            '$("' . $this->idc() . '").on("' . WEvent::CONTENTMODIFY . '", function(event) {' .
            'cm' . $this->id() . '.save();' .
            '});');

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

    /**
     * Create a new code editor.
     * @param string $id The id.
     * @param int|null $language The programming language.
     * @param string[]|string $classes Some classes.
     */
    public static function createIt(string $id, ?int $language = null, string|null $value = null,
                                    array|string $classes = []) : CodeEditorHtmlView {
        $editor = new CodeEditorHtmlView($id, $language, $classes);
        if (!is_null($value)) $editor->texts($value);
        return $editor;
    }
}
?>