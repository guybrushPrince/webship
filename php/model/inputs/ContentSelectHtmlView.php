<?php

namespace webship\inputs;

use webship\interoperability\WEvent;
use Exception;
use progression\NotImplementedException;
use progression\ui\Representer;
use ship\interoperability\IShippable;
use ship\interoperability\ShipperRegister;
use ship\interoperability\SLanguage as Language;
use webship\containers\ContainerHtmlView;
use webship\core\HtmlView;
use webship\protocols\HtmlActionProtocol;
use webship\WebshipTools;

/**
 * View ContentSelectHtmlView.
 *
 * <p>A select view, which overcomes the limitation of browser-own implementations not supporting HTML elements as
 * options.</p>
 *
 * <code>
 * $select = ContentSelectHtmlView::createIt('select-1', 3);
 * $select->each(Project::getAll(), function (ContentSelectHtmlView $select, Project $project) {
 *     $select->option($project);
 * });
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/contentselect.png" />
 *
 * @see SelectHtmlView
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     $select = ContentSelectHtmlView::createIt('select-1', 3);
 *     $select->each(Project::getAll(), function (ContentSelectHtmlView $select, Project $project) {
 *         $select->option($project);
 *     });
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
     * @var IShippable|string|null
     */
    private IShippable|string|null $default;

    /**
     * If the default view was already added.
     * @var OptionHtmlView|null
     */
    private ?OptionHtmlView $defaultOption = null;

    /**
     * The maximum of available selections.
     * @var int|null
     */
    private ?int $maxSelections;

    /**
     * Whether there should be a minimal represented to the user.
     * @var bool
     */
    private bool $mini = false;

    /**
     * Constructor.
     * @param int|string $id The id of the select.
     * @param string $name The name of the select.
     * @param string[]|string $classes Some classes.
     * @param IShippable|array|string|null $default The default value.
     */
    protected function __construct(int|string $id, string $name, array|string $classes = [],
                                   array|IShippable|string|null $default = null) {
        if (!is_array($default)) {
            $this->default = $default;
        } else $this->default = $default[1];
        $this->contentOptions = new ContainerHtmlView(['c-list', 'ver']);
        $this->contentOptions->ref('entry-list');
        parent::__construct($id, $name, $classes, true, $default);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.content-select', [], 'position: relative',
            'min-width: 100px', '--closed-scale: 1', '--closed-translate: none', '--closed-width: initial',
            '--closed-height: initial', 'padding-right: 13px', '--list-height: 40px', 'padding-left: 5px',
            'height: 40px', 'min-height: 40px'));

        self::addStyle(WebshipTools::css('.content-select.mini', [], 'min-height: 20px', 'height: 20px',
            'max-height: 20px', '--list-height: 20px'));

        self::addStyle(WebshipTools::css('.content-select', [' > ', '.c-list'], 'z-index: 10',
            'position: fixed',
            'min-width: 150px', 'max-height: 400px', 'flex-wrap: nowrap', 'overflow-y: auto',
            'width: -moz-fit-content',
            'width: fit-content', 'background: var(--neutral)', 'align-items: stretch',
            'border: 2px solid var(--light-system-background)', 'box-sizing: content-box',
            'overflow-x: hidden'));

        self::addStyle(WebshipTools::css('.content-select:not(.' . self::_OPEN . ')', [' > ', '.c-list'],
            'min-width: auto', 'z-index: initial',
            'width: fit-content', 'height: var(--list-height)', 'min-height: var(--list-height)',
            'max-height: var(--list-height)', 'padding: 0',
            'position: initial', 'gap: 5px',
            'border: none', 'align-items: center', 'justify-content: flex-start', 'overflow-y: hidden',
            'flex-wrap: nowrap', 'flex-direction: row'));

        self::addStyle(WebshipTools::css('.content-select', [' > ', '.c-list', ' > ', 'div'],
            'position: relative', 'cursor: pointer', 'padding: 4px', /*'min-height: 40px',*/
            'border-bottom: 1px solid var(--light-system-background)', 'box-sizing: content-box', 'margin: 0'));
        self::addStyle(WebshipTools::css('.content-select', [' > ', '.c-list', ' > ', 'div:last-child'],
            'border-bottom: none'));

        self::addStyle(WebshipTools::css('.content-select', [' > ', '.c-list', ' > ', 'div.' . self::_SELECTED],
            'background: var(--login-panel)'));

        self::addStyle(WebshipTools::css('.content-select', [' > ', '.c-list', ' > ', 'div:after'],
            'content: ""', 'position: absolute', 'width: 100%', 'height: 100%', 'left: 0', 'top: 0'));
        self::addStyle(WebshipTools::css('.content-select', [' > ', '.c-list', ' > ', 'div:hover'],
            'background: var(--light-system-background)'));
        self::addStyle(WebshipTools::css('.content-select', [' > ', '.c-list', ' > ', 'div.def', ' > ', '*'],
            'min-height: 32px'));
        self::addStyle(WebshipTools::css('.content-select:not(.' . self::_OPEN . ')',
            [' > ', '.c-list', ' > ', 'div.def', ' > ', '*'], 'min-height: initial',
            'height: 100%', 'padding: 0 5px'));
        self::addStyle(WebshipTools::css('.content-select.' . self::_OPEN, [' > ', '.c-list', ' > ',
            'div:before'],
            'content: ""', 'position: absolute', 'width: 5px', 'height: 100%', 'left: 0', 'top: 0'));
        self::addStyle(WebshipTools::css('.content-select.' . self::_OPEN, [' > ', '.c-list', ' > ',
            'div.' . self::_SELECTED . ':before'],'background: var(--login-border)'));

        self::addStyle(WebshipTools::css('.content-select:not(.' . self::_OPEN . ')', [' > ', '.c-list', ' > ',
            'div:not(.' . self::_SELECTED . ')'],
            'display: none'));
        self::addStyle(WebshipTools::css('.content-select:not(.' . self::_OPEN . ')', [' > ', '.c-list', ' > ',
            'div.' . self::_SELECTED],
            'background-color: inherit', 'border: none', 'padding: 0', 'flex-wrap: nowrap'));
        self::addStyle(WebshipTools::css('.content-select:not(.' . self::_OPEN . ')', [' > ', '.c-list', ' > ',
            'div.' . self::_SELECTED, ' > ', '*'],'scale: var(--closed-scale)',
            'margin: var(--closed-translate)'
        ));
        self::addStyle(WebshipTools::css('.content-select:not(.' . self::_OPEN . ')',
            [' > ', '.c-list', ' > ', 'div.def.' . self::_SELECTED], 'width: 100%', 'height: 100%',
            'translate: none', 'scale: none', 'min-height: initial'));

        self::addStyle(WebshipTools::css('.content-select', [' > ', '.overlay'],
            'position: absolute', 'width: 100%', 'height: 100%', 'left: 0', 'top: 0',
            'cursor: pointer', 'display: none',
            'box-sizing: border-box', 'border: 1px solid var(--border-3)', 'border-radius: 2px'));
        self::addStyle(WebshipTools::css('.content-select.mini', [' > ', '.overlay'],
            'border-color: var(--font-info)', 'border-radius: 5px'));
        self::addStyle(WebshipTools::css('.content-select:not(' . self::_OPEN . ')', [' > ', '.overlay'],
            'display: initial'));
        self::addStyle(WebshipTools::css('.content-select', [' > ', '.overlay:after'], 'position: absolute',
            'right: 0', 'top: 0', 'height: 100%', 'width: 7px', 'margin-right: 3px', 'content: ""',
            'background-image: url("' .
            HtmlActionProtocol::$serverURL . '/webship/pic/arrow-down-sign-to-navigate.svg")',
            'background-repeat: no-repeat', 'background-size: 7px 7px', 'background-position-y: center'));
        self::addStyle(WebshipTools::css('.content-select.mini', [' > ', '.overlay:after'],
            'filter: brightness(0) saturate(100%) invert(39%) sepia(18%) saturate(1267%) hue-rotate(155deg) brightness(97%) contrast(98%)'));
    }

    /**
     * Creates a new content-select.
     * @param int|string $id The id of the select.
     * @param int|null $maxSelections The number of maximal selections.
     * @param string[]|string $classes Some classes.
     * @param IShippable|array|string|null $default The default value if nothing is selected.
     * @return ContentSelectHtmlView
     */
    public static function createIt(int|string $id, ?int $maxSelections = 1, array|string $classes = [],
                                    IShippable|array|string|null $default = '-1') : ContentSelectHtmlView {
        $select = new ContentSelectHtmlView($id, $id, $classes, $default);
        self::addFunctionality($select, $maxSelections, $default);
        return $select;
    }

    /**
     * Add standard functionality.
     * @param ContentSelectHtmlView $select The content select.
     * @param int|null $maxSelections The maximum number of selections.
     * @param IShippable|array|string|null $default The default value.
     * @return ContentSelectHtmlView
     */
    protected static function addFunctionality(ContentSelectHtmlView $select, ?int $maxSelections = 1,
                                               IShippable|array|string|null $default = '-1') : ContentSelectHtmlView {
        if (is_array($default)) $default = $default[1];
        if ($default instanceof IShippable) $default = ShipperRegister::getObjectString($default);
        $select->maxSelections = $maxSelections;
        if (is_null($maxSelections)) $maxSelections = 'null';
        if (is_null($default)) $default = 'null';
        $select->ref('con-select');
        $select->protocol(WEvent::CLICK, ['sameTarget' => true])->doIt('ToggleClass', [
            'view' => $select,
            'class' => 'open'
        ], 'basics');
        $select->protocol(WEvent::CLICK_OUTSIDE)->doIt('RemoveClass', [
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
     * @throws NotImplementedException
     */
    public function option($value, $classes = [], $selected = false) : OptionHtmlView {
        if (!is_array($value) && !($value instanceof IShippable) && $value !== $this->default)
            throw new NotImplementedException();
        $addClass = [];
        $isDefault = false;

        // Get comparable values.
        $cValue = $value;
        if (is_array($value)) $cValue = $value[1];
        if ($cValue instanceof IShippable) $cValue = ShipperRegister::getObjectString($cValue);

        $cDefault = $this->default;
        if ($cDefault instanceof IShippable) $cDefault = ShipperRegister::getObjectString($cDefault);

        if (!is_array($value) && $cValue === $cDefault) {
            if (!is_null($this->defaultOption)) return $this->defaultOption;
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
            $isDefault = true;
        } else if (is_array($value) && $cValue === $cDefault) {
            if (!is_null($this->defaultOption)) return $this->defaultOption;
            $value = [
                $value[0],
                is_null($value[1]) ? 'undefined' : $value[1]
            ];
            $addClass[] = 'def';
            $isDefault = true;
        } else if (!is_array($value)) {
            $value = [
                Representer::getVisualRepresentation($value, self::getLanguage(), false),
                $value
            ];
        }
        $view = $value[0];
        $value = $value[1];
        $option = parent::option($value, $classes, $selected);
        if ($isDefault) $this->defaultOption = $option;
        $view->data(self::_DATA_VALUE, $option->getValue());
        if ($selected) $c = [ self::_SELECTED ];
        else $c = [];
        $this->contentOptions->item(array_merge($c, $addClass))->add($view);
        return $option;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $hasSelected = false;
        foreach ($this->subviews as $view) {
            if ($view instanceof OptionHtmlView) {
                if ($view->isSelected()) {
                    $hasSelected = true;
                    break;
                }
            }
        }
        if (!$hasSelected && !WebshipTools::isEmpty($this->contentOptions->subviews)) {
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
     * @throws Exception
     */
    public function generateClosingTag() : string {
        $overlay = new HtmlView('overlay');
        $overlay->js('$("' . $overlay->idc() . '").on("click", (e) => { setOpenSelectPosition(' .
            '$("div' . $this->idc(). '")); scaleChildren($("' . $this->idc() . '").children(".c-list")); });');
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