<?php

/**
 * Class ContentSelectHtmlView.
 *
 * A select view, which overcomes the limitation of browser-own implementations not supporting HTML elements as
 * options.
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class ContentSelectHtmlView extends SelectHtmlView {

    public const _CLASS = 'content-select';
    public const _DATA_VALUE = 'opt-value';
    public const _OPEN = 'open';
    public const _SELECTED = 'selected';

    /**
     * The HtmlView representations of the options within a container view.
     * @var ContainerHtmlView
     */
    private ContainerHtmlView $contentOptions;

    /**
     * The default value of the select if nothing is selected.
     * @var string|null
     */
    private string|null $default;

    /**
     * The maximum of available selections.
     * @var int|null
     */
    private ?int $maxSelections;

    /**
     * Whether their should be a minimal represented to the user.
     * @var bool
     */
    private bool $mini = false;

    /**
     * Constructor.
     * @param int|string $id The id of the select.
     * @param string $name The name of the select.
     * @param string[]|string $classes Some classes.
     * @param string|null $default The default value.
     */
    private function __construct(int|string $id, string $name, array|string $classes = [],
                                 string|null $default = null) {
        $this->default = $default;
        $this->contentOptions = new ContainerHtmlView(['c-list', 'ver']);
        $this->contentOptions->ref('entry-list');
        parent::__construct($id, $name, $classes, true, $default);

        $this->style(Tools::css('.content-select', [], 'position: relative',
            'min-width: 100px', '--closed-scale: 1', '--closed-translate: none', '--closed-width: initial',
            '--closed-height: initial', 'padding-right: 13px', '--list-height: 40px', 'padding-left: 5px',
            'height: 40px', 'min-height: 40px'));
        $this->style(Tools::css('.content-select.mini', [], 'min-height: 20px', 'height: 20px',
            'max-height: 20px', '--list-height: 20px'));

        $this->style(Tools::css('.content-select', [' > ', '.c-list'], 'z-index: 10',
            'position: fixed',
            'min-width: 150px', 'max-height: 400px', 'flex-wrap: nowrap', 'overflow-y: auto',
            'width: -moz-fit-content',
            'width: fit-content', 'background: var(--neutral)', 'align-items: stretch',
            'border: 2px solid var(--light-system-background)', 'box-sizing: content-box',
            'overflow-x: hidden'));

        $this->style(Tools::css('.content-select:not(.' . self::_OPEN . ')', [' > ', '.c-list'],
            'min-width: auto', 'z-index: initial',
            'width: fit-content', 'height: var(--list-height)', 'min-height: var(--list-height)',
            'max-height: var(--list-height)', 'padding: 0',
            'position: initial', 'gap: 5px',
            'border: none', 'align-items: center', 'justify-content: flex-start', 'overflow-y: hidden',
            'flex-wrap: nowrap', 'flex-direction: row'));

        $this->style(Tools::css('.content-select', [' > ', '.c-list', ' > ', 'div'],
            'position: relative', 'cursor: pointer', 'padding: 4px', /*'min-height: 40px',*/
            'border-bottom: 1px solid var(--light-system-background)', 'box-sizing: content-box', 'margin: 0'));
        $this->style(Tools::css('.content-select', [' > ', '.c-list', ' > ', 'div:last-child'],
            'border-bottom: none'));
        $this->style(Tools::css('.content-select', [' > ', '.c-list', ' > ', 'div.' . self::_SELECTED],
            'background: var(--login-panel)'));
        $this->style(Tools::css('.content-select', [' > ', '.c-list', ' > ', 'div:after'],
            'content: ""', 'position: absolute', 'width: 100%', 'height: 100%', 'left: 0', 'top: 0'));
        $this->style(Tools::css('.content-select', [' > ', '.c-list', ' > ', 'div:hover'],
            'background: var(--light-system-background)'));
        $this->style(Tools::css('.content-select', [' > ', '.c-list', ' > ', 'div.def', ' > ', '*'],
            'min-height: 32px'));
        $this->style(Tools::css('.content-select:not(.' . self::_OPEN . ')',
            [' > ', '.c-list', ' > ', 'div.def', ' > ', '*'], 'min-height: initial',
            'height: 100%', 'padding: 0 5px'));
        $this->style(Tools::css('.content-select.' . self::_OPEN, [' > ', '.c-list', ' > ',
            'div:before'],
            'content: ""', 'position: absolute', 'width: 5px', 'height: 100%', 'left: 0', 'top: 0'));
        $this->style(Tools::css('.content-select.' . self::_OPEN, [' > ', '.c-list', ' > ',
            'div.' . self::_SELECTED . ':before'],'background: var(--login-border)'));

        $this->style(Tools::css('.content-select:not(.' . self::_OPEN . ')', [' > ', '.c-list', ' > ',
            'div:not(.' . self::_SELECTED . ')'],
            'display: none'));
        $this->style(Tools::css('.content-select:not(.' . self::_OPEN . ')', [' > ', '.c-list', ' > ',
            'div.' . self::_SELECTED],
            'background-color: inherit', 'border: none', 'padding: 0', 'flex-wrap: nowrap'));
        $this->style(Tools::css('.content-select:not(.' . self::_OPEN . ')', [' > ', '.c-list', ' > ',
            'div.' . self::_SELECTED, ' > ', '*'],'scale: var(--closed-scale)',
            'margin: var(--closed-translate)'
        ));
        $this->style(Tools::css('.content-select:not(.' . self::_OPEN . ')',
            [' > ', '.c-list', ' > ', 'div.def.' . self::_SELECTED], 'width: 100%', 'height: 100%',
            'translate: none', 'scale: none', 'min-height: initial'));

        $this->style(Tools::css('.content-select', [' > ', '.overlay'],
            'position: absolute', 'width: 100%', 'height: 100%', 'left: 0', 'top: 0',
            'cursor: pointer', 'display: none',
            'box-sizing: border-box', 'border: 1px solid var(--border-3)', 'border-radius: 2px'));
        $this->style(Tools::css('.content-select.mini', [' > ', '.overlay'],
            'border-color: var(--font-info)', 'border-radius: 5px'));
        $this->style(Tools::css('.content-select:not(' . self::_OPEN . ')', [' > ', '.overlay'],
            'display: initial'));
        $this->style(Tools::css('.content-select', [' > ', '.overlay:after'], 'position: absolute',
            'right: 0', 'top: 0', 'height: 100%', 'width: 7px', 'margin-right: 3px', 'content: ""',
            'background-image: url("' .
            HtmlActionProtocol::$serverURL . '/webship/pic/arrow-down-sign-to-navigate.svg")',
            'background-repeat: no-repeat', 'background-size: 7px 7px', 'background-position-y: center'));
        $this->style(Tools::css('.content-select.mini', [' > ', '.overlay:after'],
            'filter: brightness(0) saturate(100%) invert(39%) sepia(18%) saturate(1267%) hue-rotate(155deg) brightness(97%) contrast(98%)'));

    }

    /**
     * Creates a new content-select.
     * @param int|string $id The id of the select.
     * @param int|null $maxSelections The number of maximal selections.
     * @param string[]|string $classes Some classes.
     * @param string|null $default The default value if nothing is selected.
     * @return ContentSelectHtmlView
     */
    public static function create(int|string $id, ?int $maxSelections = 1, array|string $classes = [],
                                  ?string $default = '-1') : ContentSelectHtmlView {
        $select = new ContentSelectHtmlView($id, $id, $classes, $default);
        $select->maxSelections = $maxSelections;
        if (is_null($maxSelections)) $maxSelections = 'null';
        if (is_null($default)) $default = 'null';
        $select->ref('con-select');
        $select->protocol(Event::CLICK, ['sameTarget' => true])->doIt('ToggleClass', [
            'view' => $select,
            'class' => 'open'
        ], 'basics');
        $select->protocol(Event::CLICK_OUTSIDE)->doIt('RemoveClass', [
            'view' => $select,
            'class' => 'open'
        ], 'basics')->scrollTop($select->contentOptions);
        $select->js('scaleChildren($("' . $select->idc() . '").children(".c-list"));');
        $select->js('addConSelectFunctionality($("' . $select->idc() . '"), ' . $maxSelections . ', ' . $default . ');');
        $select->js('applySelectChanges($("select' . $select->idc() . '"));');
        return $select;
    }

    /**
     * @inheritDoc
     */
    public function option($value, $classes = array(), $selected = false) : OptionHtmlView {
        if (!is_array($value) && !($value instanceof CliffSerializable) && $value !== $this->default)
            throw new NotImplementedException();
        $addClass = [];
        if ($value === $this->default) {
            $view = new ContainerHtmlView(array_merge(['hor', 'vcent', 'hcent', 'w100', 'def'], $classes));
            $view->texts([
                Language::ENGLISH => 'Please select',
                Language::GERMAN  => 'Bitte auswählen'
            ]);
            $value = [
                $view,
                is_null($value) ? 'undefined' : $value
            ];
            $addClass[] = 'def';
        } else if (!is_array($value)) {
            $value = [
                Representer::getVisualRepresentation($value, self::getLanguage(), false),
                $value
            ];
        }
        $view = $value[0];
        $value = $value[1];
        $option = parent::option($value, $classes, $selected);
        $view->data(self::_DATA_VALUE, $option->getValue());
        if ($selected) $c = [ self::_SELECTED ];
        else $c = [];
        $this->contentOptions->item(array_merge($c, $addClass))->add($view);
        return $option;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $hasSelected = false;
        foreach ($this->subviews as $view) {
            if ($view instanceof OptionHtmlView) {
                if ($view->isSelected()) {
                    $hasSelected = true;
                    break;
                }
            }
        }
        if (!$hasSelected && !Tools::isEmpty($this->contentOptions->subviews)) {
            $this->contentOptions->subviews[0]->addClass(self::_SELECTED);
        }
        $code = parent::generateOpeningTag();
        if (is_null($this->maxSelections) || $this->maxSelections >= 2) {
            $code = str_replace('<select', '<select multiple', $code);
        }
        $cl = [ self::_CLASS, $this->id() ];
        if ($this->mini) $cl[] = 'mini';
        return '<div class="' . implode(' ', $cl) . '">' . $code;
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        $overlay = new HtmlView('overlay');
        /*$overlay->protocol(Event::CLICK, ['sameTarget' => true])->doIt('ToggleClass', [
            'view' => $this,
            'class' => 'open'
        ], 'basics');*/
        $overlay->js('$("' . $overlay->idc() . '").on("click", (e) => { setOpenSelectPosition($("div' . $this->idc(). '")); scaleChildren($("' . $this->idc() . '").children(".c-list")); });');
        return parent::generateClosingTag() . $this->contentOptions->generate(true) .
            $overlay->generate(true) . '</div>';
    }

    /**
     * Sets the select to mini.
     * @return $this
     */
    public function mini() : static {
        $this->mini = true;
        return $this;
    }
}
?>