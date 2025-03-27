<?php

/**
 * Class LabelledSelectHtmlView.
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
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
     * @param string $default The default value.
     */
    private function __construct(string|array|null $labels, string $id, array|string $classes = [],
                                 string $default = '-1') {
        parent::__construct($id, $id, $classes, false, $default);
        if ($labels === null) $labels = '';
        if ($labels === '') $this->labelLess = true;
        $this->labels = is_array($labels) ? $labels : [self::getLanguage() => $labels];

        $this->style(Tools::css('.lselect', [], 'position: relative', 'width: fit-content',
            'box-sizing: border-box', 'width: -moz-fit-content', '--height: 30px', '--buffer: 10px',
            'height: calc(var(--height) + var(--buffer))',
            'display: flex', 'flex-direction: row-reverse', 'align-items: flex-end', 'flex-wrap: nowrap'));
        $this->style(Tools::css('.lselect', [' > ', 'select'], 'height: var(--height)',
            'box-sizing: border-box', 'font-size: 13px'));
        $this->style(Tools::css('.lselect.ll', [], '--buffer: 0'));
        $this->style(Tools::css('.lselect.ll', [' > ', 'select + label'], 'display: none'));

        $this->style(Tools::css('.lselect:not(.mini)', [' > ', 'select + label'],
            'position: absolute', 'box-sizing: border-box', 'left: 0', 'color: var(--dark-system-background)',
            'bottom: calc(100% - var(--buffer))', 'font-size: 10px'));

        $this->style(Tools::css('.lselect.mini', [], '--height: 20px', '--buffer: 0'));
        $this->style(Tools::css('.lselect.mini', [' > ', 'select'], 'border-radius: 3px',
            'font-size: 10px', 'border: 1px solid var(--font-info)', 'color: var(--font-info)', 'padding: 0'));
        $this->style(Tools::css('.lselect.mini', [' > ', 'select:focus'], 'outline: none'));
        $this->style(Tools::css('.lselect.mini', [' > ', 'label'], 'color: var(--font-info)',
            'font-size: 10px', 'display: flex', 'flex-wrap: nowrap', 'flex-direction: row', 'height: var(--height)',
            'width: fit-content', 'width: -moz-fit-content', 'align-items: center'));
        $this->style(Tools::css('.lselect.mini', [' > ', 'label:after'], 'color: var(--font-info)',
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
     * @param string $default The default value.
     * @return LabelledSelectHtmlView
     */
    public static function createSelect(string|array|null $labels, string $id,
                                        array|string $classes = [], string $default = '-1') : LabelledSelectHtmlView {
        return new LabelledSelectHtmlView($labels, $id, $classes, $default);
    }

    /**
     * Creates a mini text field.
     * @param string|string[]|null $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param string $default The default value.
     * @return LabelledSelectHtmlView
     */
    public static function createMiniSelect(string|array|null $labels, string $id,
                                            array|string $classes = [], string $default = '-1') : LabelledSelectHtmlView {
        $input = new LabelledSelectHtmlView($labels, $id, $classes, $default);
        $input->mini = true;
        return $input;
    }

}
?>