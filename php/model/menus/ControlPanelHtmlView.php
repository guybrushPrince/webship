<?php

namespace webship\menus;

use webship\buttons\StandardButtonHtmlView;
use webship\core\HtmlView;
use webship\links\LinkHtmlView;
use webship\WebshipTools;

/**
 * View ControlPanelHtmlView.
 *
 * <p> A grid based layout container for arranging interactive elements, such as buttons, in a control panel</p>
 *
 * <code>
 * $panel = ControlPanelHtmlView::create(4, 2);
 * $panel->button('edit', '/webship/pic/edit.svg');
 * $panel->button('delete', '/webship/pic/trash.svg');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/controlpanel.png" />
 *
 * @title ControlPanel
 * @category menus
 * 
 * @package webship
 * @subpackage php/model/symbols
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $panel = ControlPanelHtmlView::create(4, 2);
 *     $panel->button('edit', '/webship/pic/edit.svg');
 *     $panel->button('delete', '/webship/pic/trash.svg');
 */
class ControlPanelHtmlView extends HtmlView {

    /**
     * The x-dimension.
     * @var int|null
     */
    private ?int $xDim;

    /**
     * The y-dimension.
     * @var int
     */
    private int $yDim;


    /**
     * The top distance (if any and fixed).
     * @var string|int|null
     */
    private string|int|null $top = null;

    /**
     * Constructor.
     * @param int|null $xDim The x dimension (in number of cells or NULL if unbounded).
     * @param int $yDim The y dimension(in number of cells).
     * @param bool $border Whether the control panel shall be displayed with or without border.
     * @param string|string[] $classes Some classes.
     */
    private function __construct(?int $xDim = 2, int $yDim = 2, bool $border = true, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(['ctr'], $classes));

        if ($border) $this->classes[] = 'wb';
        if ($xDim === null) {
            $this->classes[] = 'ub';
            $this->classes[] = 'flexibleX';
        }

        $this->xDim = $xDim;
        $this->yDim = $yDim;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.ctr', [], 'display: grid',
            '--ws-ctr-cell-height: 30px', '--ws-ctr-cell-width: 30px', '--ws-ctr-cell-gap: 2px',
            'grid-template-columns: repeat(var(--ws-ctr-cols), 1fr)', 'grid-template-rows: repeat(var(--ws-ctr-rows), 1fr)',
            'box-sizing: content-box',
            'min-height: calc(var(--ws-ctr-cell-height) * var(--ws-ctr-rows) + var(--ws-ctr-cell-gap) * (var(--ws-ctr-rows) - 1))',
            'min-width: calc(var(--ws-ctr-cell-width) * var(--ws-ctr-cols) + var(--ws-ctr-cell-gap) * (var(--ws-ctr-cols) - 1))',
            'height: calc(var(--ws-ctr-cell-height) * var(--ws-ctr-rows) + var(--ws-ctr-cell-gap) * (var(--ws-ctr-rows) - 1))',
            'width: calc(var(--ws-ctr-cell-width) * var(--ws-ctr-cols) + var(--ws-ctr-cell-gap) * (var(--ws-ctr-cols) - 1))',
            'column-gap: var(--ws-ctr-cell-gap)', 'row-gap: var(--ws-ctr-cell-gap)', 'padding: 3px',
            'grid-auto-rows: 0', 'overflow: hidden', 'align-items: center'));
        self::addStyle(WebshipTools::css('.ctr.wb', [], 'border: 1px solid var(--ws-std-bg)',
            'border-radius: 5px'));
        self::addStyle(WebshipTools::css('.ctr.ub', [], 'width: fit-content', 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap'));
        self::addStyle(WebshipTools::css('.ctr.ub.gap5', [], '--ws-ctr-cell-gap:  5px'));
        self::addStyle(WebshipTools::css('.ctr.ub.gap10', [], '--ws-ctr-cell-gap: 10px'));
        self::addStyle(WebshipTools::css('.ctr.ub.gap15', [], '--ws-ctr-cell-gap: 15px'));
        self::addStyle(WebshipTools::css('.ctr.ub.gap20', [], '--ws-ctr-cell-gap: 20px'));
        self::addStyle(WebshipTools::css('.ctr.ub.gap25', [], '--ws-ctr-cell-gap: 25px'));
        self::addStyle(WebshipTools::css('.ctr.ub.reverse', [], 'flex-direction: row-reverse'));

        self::addStyle(WebshipTools::css('.ctr', [' ', '.ctr.area'], 'display: grid',
            'grid-column-end: span var(--ws-ctr-cols)', 'grid-row-end: span var(--ws-ctr-rows)',
            'grid-template-columns: repeat(var(--ws-ctr-cols), 1fr)', 'grid-template-rows: repeat(var(--ws-ctr-rows), 1fr)',
            'box-sizing: content-box',
            'min-height: calc(var(--ws-ctr-cell-height) * var(--ws-ctr-rows) + var(--ws-ctr-cell-gap) * (var(--ws-ctr-rows) - 1))',
            'min-width: calc(var(--ws-ctr-cell-width) * var(--ws-ctr-cols) + var(--ws-ctr-cell-gap) * (var(--ws-ctr-cols) - 1))',
            'height: calc(var(--ws-ctr-cell-height) * var(--ws-ctr-rows) + var(--ws-ctr-cell-gap) * (var(--ws-ctr-rows) - 1))',
            'width: calc(var(--ws-ctr-cell-width) * var(--ws-ctr-cols) + var(--ws-ctr-cell-gap) * (var(--ws-ctr-cols) - 1))',
            'column-gap: 2px', 'row-gap: 2px', 'padding: 0px',
            'grid-auto-rows: 0', 'overflow: visible', 'align-items: center'));
        self::addStyle(WebshipTools::css('.ctr', [' ', '.ctr.area.wb'], 'border: 1px solid var(--ws-ctr-brd)',
            'border-radius: 5px'));
        self::addStyle(WebshipTools::css('.ctr', [' ', '.ctr.area', ' > ', 'div'], 'height: 100%'));

        self::addStyle(WebshipTools::css('.ctr.flexibleX', [], 'min-width: 0', 'width: fit-content',
            'width: -moz-fit-content'));
        self::addStyle(WebshipTools::css('.ctr.flexibleY', [',', '.ctr.fix.flexibleY'], 'min-height: 0',
            'height: fit-content', 'height: -moz-fit-content', 'grid-template-rows: min-content 1fr'));

        self::addStyle(WebshipTools::css('.ctr', [' ', '.std-job-but'], 'grid-column-end: span 2'));
        self::addStyle(WebshipTools::css('.ctr', [' ', '.del:hover'], 'background: var(--ws-del-tr)'));
        self::addStyle(WebshipTools::css('.ctr', [' ', '.css-gradient'], 'height: 100%'));
        self::addStyle(WebshipTools::css('.ctr', [' ', '.css-gradient', ' > ', 'div'], 'height: 100%', 'top: 0'));

        self::addStyle(WebshipTools::css('.ctr.fix', [], 'position: absolute', 'min-height: 20px', 'height: 20px',
            'margin: 0 !important', '--ws-ctr-cell-height: 20px', '--ws-ctr-cell-width: 20px', 'z-index: 1'));
        self::addStyle(WebshipTools::css('.ctr.fix.tl', [], 'left: 0', 'top: var(--ws-ctr-top-dist)'));
        self::addStyle(WebshipTools::css('.ctr.fix.tr', [], 'right: 0', 'top: var(--ws-ctr-top-dist)'));
        self::addStyle(WebshipTools::css('.ctr.fix.bl', [], 'left: 0', 'bottom: 0'));
        self::addStyle(WebshipTools::css('.ctr.fix.br', [], 'right: 0', 'bottom: 0'));
        self::addStyle(WebshipTools::css('.ctr.fix', [' ', 'button.std-button'], 'box-shadow: none',
            '-webkit-box-shadow: none', '-moz-box-shadow: none', 'background: none', 'border-color: var(--ws-font-info)',
            'width: 20px', 'height: 20px', 'min-width: 20px', 'min-height: 20px', 'padding: 3px'));
        self::addStyle(WebshipTools::css('.ctr.fix', [' ', 'button.std-button', ':hover'],
            'background: var(--ws-font-info)'));
        self::addStyle(WebshipTools::css('.ctr.fix', [' ', 'button.std-button', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(39%) sepia(18%) saturate(1267%) hue-rotate(155deg) brightness(97%) contrast(98%)',
            'transition: filter 0.25s'));
        self::addStyle(WebshipTools::css('.ctr.fix', [' ', 'button.std-button', ':hover', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(241deg) brightness(110%) contrast(106%)'));
        self::addStyle(WebshipTools::css('.ctr.fix', [' ', 'button.std-button.del', ':hover'],
            'background: var(--ws-del-tr)', 'border-color: var(--ws-neutral)'));

        self::addStyle(WebshipTools::css('.ctr.small', [], 'min-height: 20px', 'height: 20px',
            'margin: 0 !important', '--ws-ctr-cell-height: 20px', '--ws-ctr-cell-width: 20px'));
        self::addStyle(WebshipTools::css('.ctr.small', [' ', 'button.std-button'], 'box-shadow: none',
            '-webkit-box-shadow: none', '-moz-box-shadow: none', 'background: none', 'border-color: var(--ws-font-info)',
            'width: 20px', 'height: 20px', 'min-width: 20px', 'min-height: 20px', 'padding: 3px'));
        self::addStyle(WebshipTools::css('.ctr.small.wip', [], 'padding: 0 3px',
            'align-items: baseline'));

        self::addStyle(WebshipTools::css('.ctr.plain', [' ', 'button.std-button'], 'border: none'));
        self::addStyle(WebshipTools::css('.ctr.plain.wip', [], 'padding: 0 3px',
            'align-items: baseline'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $tag = substr(parent::generateOpeningTag(), 0, -1);
        if ($this->xDim === null) $this->xDim = 1;
        $tag .= ' style="--ws-ctr-cols: ' . $this->xDim . ';' . '--ws-ctr-rows: ' . $this->yDim . ';' .
            (!is_null($this->top) ? '--ws-ctr-top-dist: ' . $this->top . ';' : '--ws-ctr-top-dist: 0;') . '">';
        return $tag;
    }

    /**
     * Create a control panel.
     * @param int $xDim The x dimension (in number of cells).
     * @param int $yDim The y dimension(in number of cells).
     * @param bool $border Whether the control panel shall be displayed with or without border.
     * @param bool $flexibleX If its x-dimension is flexible.
     * @param bool $flexibleY If its y-dimension is flexible.
     * @param string|string[] $classes Some classes.
     * @return ControlPanelHtmlView
     */
    public static function create(int $xDim = 2, int $yDim = 2, bool $border = true, bool $flexibleX = false,
                                  bool $flexibleY = false, string|array $classes = []) : ControlPanelHtmlView {
        $panel = new ControlPanelHtmlView($xDim, $yDim, $border, $classes);
        if ($flexibleX) $panel->addClass('flexibleX');
        if ($flexibleY) $panel->addClass('flexibleY');
        return $panel;
    }

    /**
     * Create a control panel.
     * @param bool $border Whether the control panel shall be displayed with or without border.
     * @param string|string[] $classes Some classes.
     * @return ControlPanelHtmlView
     */
    public static function createUnbounded(bool $border = true, string|array $classes = []) : ControlPanelHtmlView {
        return new ControlPanelHtmlView(null, 1, $border, $classes);
    }

    /**
     * Create a button in the control panel.
     * @param string $id The id.
     * @param string $img The path to the image.
     * @param string|string[] $classes Some classes.
     * @return StandardButtonHtmlView
     */
    public function button(string $id, string $img, string|array $classes = []) : StandardButtonHtmlView {
        $but = StandardButtonHtmlView::create($id, $classes);
        $this->add($but);
        $but->img($img);
        $but->onlyImg(true);
        return $but;
    }

    /**
     * Adds an available standard button.
     * @param StandardButtonHtmlView $but The button.
     * @param string|null $img Another image.
     * @param string|string[] $classes Some additional classes.
     * @return StandardButtonHtmlView
     */
    public function availableButton(StandardButtonHtmlView $but, ?string $img = null,
                                    string|array $classes = []) : StandardButtonHtmlView {
        $this->add($but);
        if ($img) $but->img($img);
        $but->onlyImg(true);
        if (!is_array($classes)) $classes = [$classes];
        foreach ($classes as $class) $but->addClass($class);
        return $but;
    }

    /**
     * Creates a button being a link.
     * @param string $url The url.
     * @param string $img The image.
     * @param bool $download Whether it shall be downloadable, or not.
     * @param string|string[] $classes Some additional classes.
     * @return LinkHtmlView
     */
    public function linkButton(string $url, string $img, bool $download = false,
                               string|array $classes = []) : LinkHtmlView {
        $link = LinkHtmlView::create($url, LinkHtmlView::TARGET_NEW_PAGE, $classes, $download);
        $this->add($link);
        $but = StandardButtonHtmlView::create($url);
        $link->add($but);
        $but->img($img);
        $but->onlyImg(true);
        return $link;
    }

    /**
     * Create a standard delete button in the control panel.
     * @param string $id The id.
     * @param string|string[] $classes Some classes.
     * @param string $img (optional) The path to the image.
     * @return StandardButtonHtmlView
     */
    public function delete(string $id, string|array $classes = [],
                           string $img = '/webship/pic/delete.svg') : StandardButtonHtmlView {
        $but = $this->button($id, $img, $classes);
        $but->addClass('del');
        return $but;
    }

    /**
     * A separated area in the panel.
     * @param int $xDim The x dimension (in number of cells).
     * @param int $yDim The y dimension(in number of cells).
     * @param bool $border Whether the control panel shall be displayed with or without border.
     * @param bool $flexibleX If its x-dimension is flexible.
     * @param bool $flexibleY If its y-dimension is flexible.
     * @param string|string[] $classes Some classes.
     * @return ControlPanelHtmlView
     */
    public function area(int $xDim, int $yDim, bool $border = true, bool $flexibleX = false, bool $flexibleY = false,
                         string|array $classes = []) : ControlPanelHtmlView {
        $classes = is_array($classes) ? $classes : array($classes);
        $view = ControlPanelHtmlView::create($xDim, $yDim, $border, $flexibleX, $flexibleY,
            array_merge(['area'], $classes));
        $this->add($view);
        return $view;
    }

    /**
     * Fixes the control panel within aview.
     * @param bool $top If it is located in the top.
     * @param bool $right If it is located in the right.
     * @return $this
     */
    public function fix(bool $top = true, bool $right = true, string|int|null $vDist = null) : static {
        $this->addClass('fix');
        if ($top) {
            if ($right) $this->addClass('tr');
            else $this->addClass('tl');
            if (!is_null($vDist)) {
                if (is_int($vDist)) $vDist = $vDist . 'px';
                $this->top = $vDist;
            }
        } else {
            if ($right) $this->addClass('br');
            else $this->addClass('bl');
        }
        return $this;
    }

    /**
     * Add a mini-view.
     * @param HtmlView $mini The mini-view to add.
     * @param int|null $xSpan The x-axis span of the element (default 1).
     * @param int|null $ySpan The y-axis span of the element (default 1).
     * @param bool|null $ret If the added view or the ctr shall be returned.
     * @return $this|HtmlView
     */
    public function addMini(HtmlView $mini, ?int $xSpan = null, ?int $ySpan = null,
                            ?bool $ret = false) : static|HtmlView {
        if ($xSpan || $ySpan) {
            $wrapper = new HtmlView();
            if ($xSpan) $wrapper->addLocalStyle('grid-column-end: span ' . $xSpan);
            if ($ySpan) $wrapper->addLocalStyle('grid-row-end: span ' . $ySpan);
            $wrapper->add($mini);
            $mini = $wrapper;
        }
        $this->add($mini);
        if ($ret) return $mini;
        return $this;
    }

    /**
     * Sets it plain.
     * @param bool $withoutPadding If it should avoid padding.
     * @return $this
     */
    public function plain(bool $withoutPadding = false) : static {
        $this->addClass('plain');
        if ($withoutPadding) $this->addClass('wip');
        return $this;
    }

    /**
     * Sets a unbounded control panel with a reverse direction of items.
     * @return $this
     */
    public function reverse() : static {
        $this->addClass('reverse');
        return $this;
    }

    /**
     * Sets it small.
     * @param bool $withoutPadding If it should avoid padding.
     * @return $this
     */
    public function small(bool $withoutPadding = false) : static {
        $this->addClass('small');
        if ($withoutPadding) $this->addClass('wip');
        return $this;
    }
}
?>