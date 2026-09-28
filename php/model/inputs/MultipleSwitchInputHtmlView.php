<?php

namespace webship\inputs;

use ship\interoperability\IShippable;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View MultipleSwitchInputHtmlView.
 *
 * <p>A switch that allows multiple options (not only true/false).</p>
 *
 * <code>
 * $multi = MultipleSwitchInputHtmlView::create('multi');
 * $multi->each(range(1,5), function (MultipleSwitchInputHtmlView $m, int $nr) {
 *     $m->option($nr)->texts($nr);
 * });
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/multiswitchinput.png" />
 *
 * @title MultipleSwitchInput
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
 *     $multi = MultipleSwitchInputHtmlView::create('multi');
 *     $multi->each(range(1,5), function (MultipleSwitchInputHtmlView $m, int $nr) {
 *         $m->option($nr)->texts($nr);
 *     });
 */
class MultipleSwitchInputHtmlView extends HtmlView {

    /**
     * The id of the input.
     * @var string
     */
    private string $id;

    /**
     * Constructor.
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string $id, array|string $classes = []) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'mul-switch';
        parent::__construct($classes);
        $this->id = $id;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.mul-switch', [], 'display: flex', 'flex-direction: row',
            'flex-wrap: nowrap', 'flex: 1 1 0px', 'gap: 0', '--ws-ms-unselected: var(--ws-std-bg-dark)',
            '--ws-ms-unselected-font: var(--ws-neutral)', '--ws-ms-selected: var(--ws-multiple-switch)',
            '--ws-ms-selected-font: var(--ws-neutral)', '--ws-ms-hover: var(--ws-multiple-switch-hvr)',
            '--ws-ms-hover-font: var(--ws-neutral)'));

        self::addStyle(WebshipTools::css('.mul-switch', [' > ', 'input'], 'display: none', 'height: 0',
            'opacity: 0', 'width: 0'));
        self::addStyle(WebshipTools::css('.mul-switch', [' > ', 'input', ' + ', 'label'],'display: flex',
            'flex-direction: row', 'align-items: center', 'justify-content: center', 'flex-basis: 0', 'padding: 0 8px',
            'height: 25px', 'min-width: 30px', 'background: var(--ws-ms-unselected)', 'color: var(--ws-ms-unselected-font)',
            'font-weight: bolder', 'cursor: pointer', 'transition: 0.5s background'));
        self::addStyle(WebshipTools::css('.mul-switch', [' > ', 'label:first-of-type'],
            'border-radius: 3px 0 0 3px'));
        self::addStyle(WebshipTools::css('.mul-switch', [' > ', 'label:last-of-type'],
            'border-radius: 0 3px 3px 0'));
        self::addStyle(WebshipTools::css('.mul-switch', [' > ', 'input:checked', ' + ', 'label'],
            'background: var(--ws-ms-selected)', 'color: var(--ws-ms-selected-font)'));
        self::addStyle(WebshipTools::css('.mul-switch', [' > ', 'input:focus', ' + ', 'label'],
            'box-shadow: 0 0 1px var(--ws-ms-selected)'));
        self::addStyle(WebshipTools::css('.mul-switch', [' > ', 'input:not(:checked)', ' + ', 'label:hover'],
            'background: var(--ws-ms-hover)', 'color: var(--ws-ms-hover-font)'));
        self::addStyle(WebshipTools::css('.mul-switch', [' > ', 'input:disabled', ' + ', 'label'],
            'opacity: 0.7', 'background: var(--ws-ms-unselected)', 'color: var(--ws-ms-unselected-font)'));
    }

    /**
     * Create a new multiple switch.
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     */
    public static function create(string $id, array|string $classes = []) : MultipleSwitchInputHtmlView {
        return new MultipleSwitchInputHtmlView($id, $classes);
    }

    /**
     * Add an option.
     * @param IShippable|string|int|null $value The value.
     * @param bool $selected Whether it is selected.
     * @param HtmlView|null $visualize An optional visualization.
     * @param string[]|string $classes Some classes.
     * @return RadioOptionHtmlView
     */
    public function option(IShippable|string|int|null $value, bool $selected = false, ?HtmlView $visualize = null,
                           array|string $classes = []) : RadioOptionHtmlView {
        $option = RadioOptionHtmlView::create($this->id . '-' . count($this->subviews), $this->id, $value,
            $selected, $classes);
        if ($visualize) $option->add($visualize);
        $this->add($option);
        return $option;
    }

    /**
     * Enable/disable the switch.
     * @param bool $disabled Whether it shall be disabled or not.
     * @return $this
     */
    public function disabled(bool $disabled = true) : static {
        $this->addClass('disabled');
        foreach ($this->subviews as $option) $option->disabled($disabled);
        return $this;
    }
}
?>