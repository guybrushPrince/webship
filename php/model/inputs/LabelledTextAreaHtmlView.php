<?php

namespace webship\inputs;

use Tools;

/**
 * View LabelledTextAreaHtmlView.
 *
 * <p>A labelled {@link TextHtmlView} (text area).</p>
 *
 * <code>
 * LabelledTextAreaHtmlView::createTextArea('Please specify:', 'text-1');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/labelledtextarea.png" />
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     LabelledTextAreaHtmlView::createTextArea('Please specify:', 'text-1');
 */
class LabelledTextAreaHtmlView extends TextHtmlView {

    private const mainClass = 'ltext-input';
    private const miniClass = 'mini';
    private const labelLessClass = 'll';

    /**
     * Whether the input is mini or not.
     * @var bool
     */
    private bool $mini = false;

    /**
     * Whether the label can be presented inside the input.
     * @var bool
     */
    private bool $inside = true;

    /**
     * Whether the view is without labels, or not.
     * @var bool
     */
    private bool $labelLess = false;

    /**
     * The labels.
     * @var string[]
     */
    private array $labels;

    /**
     * Constructor.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param int $rows The number of rows.
     * @param int|null $max The maximum number of characters.
     * @param bool $resizable Whether the text area is resizable, or not.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string|array|null $labels, string $id,
                                 int $rows = 3, int|null $max = null, bool $resizable = false,
                                 array|string $classes = []) {
        parent::__construct($id, $id, $rows, $max, $classes, $resizable);
        if (is_null($labels)) $labels = '';
        if ($labels === '') $this->labelLess = true;
        $this->labels = is_array($labels) ? $labels : [ self::getLanguage() => $labels ];

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.ltext-input', [], 'position: relative', 'width: fit-content',
            'box-sizing: border-box', 'width: -moz-fit-content', '--height: 30px', '--buffer: 10px',
            'min-height: calc(var(--height) + var(--buffer))',
            'display: flex', 'flex-direction: row', 'align-items: flex-end', 'flex-wrap: nowrap'));
        self::addStyle(Tools::css('.ltext-input.ll', [], '--buffer: 0'));
        self::addStyle(Tools::css('.ltext-input.ll', [' > ', 'textarea + label'], 'display: none'));
        self::addStyle(Tools::css('.ltext-input', [' > ', 'textarea'], 'min-height: var(--height)',
            'box-sizing: border-box', 'margin-top: 10px'));
        self::addStyle(Tools::css('.ltext-input', [' > ', 'textarea + label'],
            'position: absolute', 'box-sizing: border-box', 'left: 0', 'color: var(--dark-system-background)',
            'transition: 0.5s'));
        self::addStyle(Tools::css('.ltext-input', [' > ', 'textarea:not(:focus):placeholder-shown + label'],
            'display: flex', 'top: var(--buffer)', 'flex-direction: row',
            'height: calc(100% - var(--buffer))', 'align-items: center', 'cursor: text', 'padding-left: 8px',
            'font-size: 13px'));
        self::addStyle(Tools::css('.ltext-input', [' > ', 'textarea:focus: + label'],
            'bottom: calc(100% - var(--buffer))', 'font-size: 10px', 'top: unset'));
        self::addStyle(Tools::css('.ltext-input', [' > ', 'textarea:not(:placeholder-shown) + label'],
            'bottom: calc(100% - var(--buffer))', 'font-size: 10px', 'top: unset'));

        self::addStyle(Tools::css('.ltext-input.mini', [], '--height: 20px', '--buffer: 0'));
        self::addStyle(Tools::css('.ltext-input.mini', [' > ', 'textarea'], 'border-radius: 3px',
            'font-size: 10px', 'border: 1px solid var(--font-info)', 'color: var(--font-info)'));
        self::addStyle(Tools::css('.ltext-input.mini', [' > ', 'textarea:focus'], 'outline: none'));
        self::addStyle(Tools::css('.ltext-input.mini', [' > ', 'textarea:placeholder-shown + label'],
            'height: var(--height)', 'color: var(--font-info)', 'font-size: 10px', 'padding-left: 5px'));
        self::addStyle(Tools::css('.ltext-input.mini', [' > ', 'textarea:focus:placeholder-shown + label'],
            'display: none'));
        self::addStyle(Tools::css('.ltext-input.mini', [' > ', 'textarea:not(:placeholder-shown) + label'],
            'display: none'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<div class="' . self::mainClass . ($this->mini ? ' ' . self::miniClass : '') .
            ($this->labelLess ? ' ' . self::labelLessClass : '') . '">' .
            parent::generateOpeningTag();
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return parent::generateClosingTag() .
            '<label for="' . $this->getId() . '" onclick="$(\'#' . $this->getId() . '[type=text]\').focus();">' .
            ($this->mini && $this->inside ? '[' : '') .
            str_replace(' ', '&nbsp;', $this->labels[self::getLanguage()]) .
            ($this->mini && $this->inside ? ']' : '') .
            '</label></div>';
    }

    /**
     * Create a text area.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param int $rows The number of rows.
     * @param int|null $max The maximum length.
     * @param string|null $value The start value.
     * @param bool $resizable Whether the text area shall be resizable, or not.
     * @param string[]|string $classes Some classes.
     * @return LabelledTextAreaHtmlView
     */
    public static function createTextArea(string|array|null $labels, string $id, int $rows = 3, int|null $max = null,
                                          ?string $value = null, bool $resizable = false,
                                          array|string $classes = []) : LabelledTextAreaHtmlView {
        $input = new LabelledTextAreaHtmlView($labels, $id, $rows, $max, $resizable, $classes);
        if (!is_null($value)) $input->texts($value);
        return $input;
    }

    /**
     * Create a mini text area.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param int $rows The number of rows.
     * @param int|null $max The maximum length.
     * @param string|null $value The start value.
     * @param bool $resizable Whether the text area shall be resizable, or not.
     * @param string[]|string $classes Some classes.
     * @return LabelledTextAreaHtmlView
     */
    public static function createMiniTextArea(string|array|null $labels, string $id, int $rows = 3, int|null $max = null,
                                              ?string $value = null, bool $resizable = false,
                                              array|string $classes = []) : LabelledTextAreaHtmlView {
        $input = self::createTextArea($labels, $id, $rows, $max, $value, $resizable, $classes);
        $input->mini = true;
        return $input;
    }
}
?>