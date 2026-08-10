<?php

namespace webship\inputs;

use ship\interoperability\IShippable;
use webship\WebshipTools;

/**
 * View LabelledSelectHtmlView.
 *
 * <p>A HTML select has no native corresponding label. This view adds a label to a select and provides different sizes
 * of the labelled select.</p>
 *
 * <code>
 * $select1 = LabelledSelectHtmlView::createSelect('Choose number:', 'number-1');
 * $select1->each(range(1, 10), function (SelectHtmlView $sel, int $nr) {
 *     $sel->option($nr)->texts($nr);
 * });
 * $select2 = LabelledSelectHtmlView::createMiniSelect('Choose number:', 'number-1');
 * $select2->each(range(1, 10), function (SelectHtmlView $sel, int $nr) {
 *     $sel->option($nr)->texts($nr);
 * });
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/labelledselect.png" />
 *
 * @title LabelledSelect
 * @category inputs
 * 
 * @package webship
 * @subpackage php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     $select1 = LabelledSelectHtmlView::createSelect('Choose number:', 'number-1');
 *     $select1->each(range(1, 10), function (SelectHtmlView $sel, int $nr) {
 *         $sel->option($nr)->texts($nr);
 *     });
 *     $select2 = LabelledSelectHtmlView::createMiniSelect('Choose number:', 'number-1');
 *     $select2->each(range(1, 10), function (SelectHtmlView $sel, int $nr) {
 *         $sel->option($nr)->texts($nr);
 *     });
 */
class LabelledSelectHtmlView extends SelectHtmlView {

    private const mainClass = 'lselect';
    private const miniClass = 'mini';
    private const labelLessClass = 'll';

    /**
     * Whether the input is mini or not.
     * @var bool
     */
    private bool $mini = false;

    /**
     * The labels.
     * @var string[]|null
     */
    private ?array $labels;

    /**
     * Does the select has labels?
     * @var bool
     */
    private bool $labelLess = false;

    /**
     * Constructor.
     * @param string|string[] $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param IShippable|string|null $default The default value.
     */
    private function __construct(string|array|null $labels, string $id, array|string $classes = [],
                                 IShippable|string|null $default = '-1') {
        parent::__construct($id, $id, $classes, false, $default);
        if ($labels === null) $labels = '';
        if ($labels === '') $this->labelLess = true;
        $this->labels = is_array($labels) ? $labels : [self::getLanguage() => $labels];

        self::addCSS();
    }

    /**
     * Generate the CSS code.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.lselect', [], 'position: relative', 'width: fit-content',
            'box-sizing: border-box', 'width: -moz-fit-content', '--ws-ls-height: 30px', '--ws-ls-buffer: 10px',
            'height: calc(var(--ws-ls-height) + var(--ws-ls-buffer))',
            'display: flex', 'flex-direction: row-reverse', 'align-items: flex-end', 'flex-wrap: nowrap'));
        self::addStyle(WebshipTools::css('.lselect', [' > ', 'select', ', ',
            '.lselect', ' > ', 'div.select'], 'height: var(--ws-ls-height)',
            'box-sizing: border-box', 'font-size: 13px'));
        self::addStyle(WebshipTools::css('.lselect.ll', [], '--ws-ls-buffer: 0'));
        self::addStyle(WebshipTools::css('.lselect.ll', [' > ', 'select + label', ', ',
            '.lselect.ll', ' > ', 'div.select + label'], 'display: none'));

        self::addStyle(WebshipTools::css('.lselect:not(.mini)', [' > ', 'select + label', ', ',
            '.lselect:not(.mini)', ' > ', 'div.select + label'],
            'position: absolute', 'box-sizing: border-box', 'left: 0', 'color: var(--ws-std-bg-dark)',
            'bottom: calc(100% - var(--ws-ls-buffer))', 'font-size: 10px'));

        self::addStyle(WebshipTools::css('.lselect.mini', [], '--ws-ls-height: 20px', '--ws-ls-buffer: 0'));
        self::addStyle(WebshipTools::css('.lselect.mini', [' > ', 'select', ', ',
            '.lselect.mini', ' > ', 'select'], 'border-radius: 3px',
            'font-size: 10px', 'border: 1px solid var(--ws-font-info)', 'color: var(--ws-font-info)', 'padding: 0'));
        self::addStyle(WebshipTools::css('.lselect.mini', [' > ', 'select:focus',
            '.lselect.mini', ' > ', 'select:focus'], 'outline: none'));
        self::addStyle(WebshipTools::css('.lselect.mini', [' > ', 'label'], 'color: var(--ws-font-info)',
            'font-size: 10px', 'display: flex', 'flex-wrap: nowrap', 'flex-direction: row', 'height: var(--ws-ls-height)',
            'width: fit-content', 'width: -moz-fit-content', 'align-items: center'));
        self::addStyle(WebshipTools::css('.lselect.mini', [' > ', 'label:after'], 'color: var(--ws-font-info)',
            'font-size: 10px', 'content: ":"', 'display: block', 'margin-right: 5px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<div class="' . self::mainClass .
            ($this->mini ? ' ' . self::miniClass : '') .
            ($this->labelLess ? ' ' . self::labelLessClass : '') . '">' .
            parent::generateOpeningTag();
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return parent::generateClosingTag() . ($this->labels ?
            '<label for="' . $this->getId() . '">' .
            str_replace(' ', '&nbsp;', $this->labels[self::getLanguage()]) .
            '</label>' : '') . '</div>';
    }

    /**
     * Create a select.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param IShippable|string|null $default The default value.
     * @return LabelledSelectHtmlView
     */
    public static function createSelect(string|array|null $labels, string $id,
                                        array|string $classes = [],
                                        IShippable|string|null $default = '-1') : LabelledSelectHtmlView {
        return new LabelledSelectHtmlView($labels, $id, $classes, $default);
    }

    /**
     * Creates a mini text field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param IShippable|string|null $default The default value.
     * @return LabelledSelectHtmlView
     */
    public static function createMiniSelect(string|array|null $labels, string $id, array|string $classes = [],
                                            IShippable|string|null $default = '-1') : LabelledSelectHtmlView {
        $input = new LabelledSelectHtmlView($labels, $id, $classes, $default);
        $input->mini = true;
        return $input;
    }
}
?>