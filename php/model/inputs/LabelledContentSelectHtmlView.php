<?php

namespace webship\inputs;

use webship\WebshipTools;

/**
 * View LabelledContentSelectHtmlView.
 *
 * <p>A labelled version of a {@link ContentSelectHtmlView}.</p>
 *
 * <code>
 * $select1 = LabelledContentSelectHtmlView::createSelect('Choose number:', 'number-1');
 * $select1->each(range(1, 10), function (SelectHtmlView $sel, int $nr) {
 *     $sel->option([ContainerHtmlView::createIt()->texts($nr . ' Number'), $nr]);
 * });
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/labelledcontentselect.png" />
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     $select1 = LabelledContentSelectHtmlView::createSelect('Choose number:', 'number-1');
 *     $select1->each(range(1, 10), function (SelectHtmlView $sel, int $nr) {
 *         $sel->option([ContainerHtmlView::createIt()->texts($nr . ' Number'), $nr]);
 *     });
 */
class LabelledContentSelectHtmlView extends ContentSelectHtmlView {

    private const mainClass = 'clselect';
    private const miniClass = 'mini';
    private const labelLessClass = 'll';

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
     * @param array|string|null $default The default value.
     */
    private function __construct(string|array|null $labels, string $id, array|string $classes = [],
                                 array|string|null $default = '-1') {
        parent::__construct($id, $id, $classes, $default);
        if ($labels === null) $labels = '';
        if ($labels === '') $this->labelLess = true;
        $this->labels = is_array($labels) ? $labels : [self::getLanguage() => $labels];

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        LabelledSelectHtmlView::addCSS();
        self::addStyle(WebshipTools::css('.clselect', [], 'position: relative', 'width: fit-content',
            'box-sizing: border-box', 'width: -moz-fit-content', '--ws-lcs-height: 40px', '--ws-lcs-buffer: 10px',
            'height: calc(var(--ws-lcs-height) + var(--ws-lcs-buffer))',
            'display: flex', 'flex-direction: row-reverse', 'align-items: flex-end', 'flex-wrap: nowrap'));
        self::addStyle(WebshipTools::css('.clselect.ll', [], '--ws-lcs-buffer: 0'));
        self::addStyle(WebshipTools::css('.clselect.ll', [' > ', '.content-select + label'], 'display: none'));

        self::addStyle(WebshipTools::css('.clselect', [' > ', '.content-select + label'],
            'position: absolute', 'box-sizing: border-box', 'left: 0', 'color: var(--ws-std-dark-bg)',
            'bottom: calc(100% - var(--ws-lcs-buffer))', 'font-size: 10px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<div class="' . self::mainClass .
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
     * Create a content select.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param int|null $maxSelections The maximum number of selection.
     * @param string[]|string $classes Some classes.
     * @param array|string|null $default The default value.
     * @return LabelledContentSelectHtmlView
     */
    public static function createSelect(string|array|null $labels, string $id, ?int $maxSelections = 1,
                                        array|string $classes = [],
                                        array|string|null $default = '-1') : LabelledContentSelectHtmlView {
        $select = new LabelledContentSelectHtmlView($labels, $id, $classes, $default);
        self::addFunctionality($select, $maxSelections, $default);
        return $select;
    }
}
?>