<?php

/**
 * View LabelledContentSelectHtmlView.
 *
 * <p> A view to create a labeled HtmlView with classes </p>
 *
 * <code>
 * $view = LabelledContentSelectHtmlView::createSelect('lable', 'lableid',1, [], [(new HtmlView())->texts('text')])
 * </code>
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 * @view
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
     * Generate the CSS code.
     * @return void
     */
    public static function addCSS() : void {
        LabelledSelectHtmlView::addCSS();
        self::addStyle(Tools::css('.clselect', [], 'position: relative', 'width: fit-content',
            'box-sizing: border-box', 'width: -moz-fit-content', '--height: 40px', '--buffer: 10px',
            'height: calc(var(--height) + var(--buffer))',
            'display: flex', 'flex-direction: row-reverse', 'align-items: flex-end', 'flex-wrap: nowrap'));
        self::addStyle(Tools::css('.clselect.ll', [], '--buffer: 0'));
        self::addStyle(Tools::css('.clselect.ll', [' > ', '.content-select + label'], 'display: none'));

        self::addStyle(Tools::css('.clselect', [' > ', '.content-select + label'],
            'position: absolute', 'box-sizing: border-box', 'left: 0', 'color: var(--dark-system-background)',
            'bottom: calc(100% - var(--buffer))', 'font-size: 10px'));
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