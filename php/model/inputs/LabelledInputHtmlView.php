<?php

/**
 * Class LabelledInputHtmlView.
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class LabelledInputHtmlView extends InputHtmlView {

    private const mainClass = 'linput';
    private const miniClass = 'mini';

    /**
     * Whether the input is mini or not.
     * @var bool
     */
    private bool $mini = false;

    /**
     * The labels.
     * @var string[]
     */
    private array $labels;

    /**
     * Constructor.
     * @param string|string[] $labels The label(s).
     * @param string $id The id.
     * @param string $type The type (either *text* or *password*).
     * @param int|null $min The minimum length.
     * @param int|null $max The maximum length.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string|array $labels, string $id, string $type,
                                ?int $min = null, ?int $max = null, array|string $classes = []) {
        parent::__construct($id, $id, $type, $min, $max, null, $classes);
        $this->labels = is_array($labels) ? $labels : [ self::getLanguage() => $labels ];
        $this->addHint(self::getLanguage(), '');

        $this->style(Tools::css('.linput', [], 'position: relative', 'width: fit-content',
            'box-sizing: border-box', 'width: -moz-fit-content', '--height: 30px', '--buffer: 10px',
            'height: calc(var(--height) + var(--buffer))',
            'display: flex', 'flex-direction: row', 'align-items: flex-end'));
        $this->style(Tools::css('.linput', [' > ', 'input'], 'height: var(--height)',
            'box-sizing: border-box'));
        $this->style(Tools::css('.linput', [' > ', 'input + label'], 'position: absolute',
            'box-sizing: border-box', 'left: 0', 'color: var(--dark-system-background)',
            'transition: 0.5s'));
        $this->style(Tools::css('.linput', [' > ', 'input:placeholder-shown + label'], 'display: flex',
            'top: var(--buffer)', 'flex-direction: row', 'width: 100%', 'height: calc(100% - var(--buffer))',
            'align-items: center', 'cursor: text', 'padding-left: 8px', 'font-size: 13px'));
        $this->style(Tools::css('.linput', [' > ', 'input:not(:placeholder-shown) + label'],
            'bottom: calc(100% - var(--buffer))', 'font-size: 10px'));

        $this->style(Tools::css('.linput.mini', [], '--height: 20px', '--buffer: 0'));
        $this->style(Tools::css('.linput.mini', [' > ', 'input'], 'border-radius: 3px',
            'font-size: 10px', 'border: 1px solid var(--font-info)', 'color: var(--font-info)'));
        $this->style(Tools::css('.linput.mini', [' > ', 'input:focus'], 'outline: none'));
        $this->style(Tools::css('.linput.mini', [' > ', 'input:placeholder-shown + label'],
            'height: var(--height)', 'color: var(--font-info)', 'font-size: 10px', 'padding-left: 5px'));
        $this->style(Tools::css('.linput.mini', [' > ', 'input:not(:placeholder-shown) + label'],
            'display: none'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<div class="' . self::mainClass . ($this->mini ? ' ' . self::miniClass : '') . '">' .
            parent::generateOpeningTag();
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return parent::generateClosingTag() .
            '<label for="' . $this->getId() . '">' .
            ($this->mini ? '[' : '') .
            $this->labels[self::getLanguage()] .
            ($this->mini ? ']' : '') .
            '</label></div>';
    }

    /**
     * Create a text input field.
     * @param string|string[] $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param int|null $min The minimum length.
     * @param int|null $max The maximum length.
     * @return LabelledInputHtmlView
     */
    public static function createTextField(string|array $labels, string $id, array|string $classes = [],
                                           ?int $min = null, ?int $max = null) : LabelledInputHtmlView {
        return new LabelledInputHtmlView($labels, $id, 'text', $min, $max, $classes);
    }

    /**
     * Creates a mini text field.
     * @param string|string[] $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param int|null $min The minimum length.
     * @param int|null $max The maximum length.
     * @return LabelledInputHtmlView
     */
    public static function createMiniField(string|array $labels, string $id, array|string $classes = [],
                                           ?int $min = null, ?int $max = null) : LabelledInputHtmlView {
        $input = new LabelledInputHtmlView($labels, $id, 'text', $min, $max, $classes);
        $input->mini = true;
        return $input;
    }

    /**
     * Create a password input field.
     * @param string|string[] $labels The label(s).
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param int|null $min The minimum length.
     * @param int|null $max The maximum length.
     * @return LabelledInputHtmlView
     */
    public static function createPasswordField(string|array $labels, string $id, array|string $classes = [],
                                               ?int $min = null, ?int $max = null) : LabelledInputHtmlView {
        return new LabelledInputHtmlView($labels, $id, 'password', $min, $max, $classes);
    }
}
?>