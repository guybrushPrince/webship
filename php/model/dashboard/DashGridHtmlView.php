<?php

namespace webship\dashboards;

use ship\interoperability\ShipperRegister;
use webship\core\HtmlView;
use webship\interoperability\IDashboardItem;
use webship\WebshipTools;

/**
 * View DashGridHtmlView.
 *
 * <p>An initializable dash-grid-based dashboard view that organizes items in columns and rows.</p>
 *
 * <code>
 * $grid = new DashGridHtmlView();
 * </code>
 *
 * @title DashGrid
 * @category dashboard
 * 
 * @package webship
 * @subpackage php/model/dashboard
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $grid = new DashGridHtmlView();
 */
class DashGridHtmlView extends HtmlView {

    const FREE = 0;
    const FILLED = 1;
    const PLACEHOLDER = 2;

    private int $initXPos = -1;
    private int $initYPos = -1;
    private int $initXDim = -1;
    private int $initYDim = -1;

    /**
     * DashGridHtmlView constructor.
     * @param string[]|string $classes Some classes.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     */
    private function __construct(array|string $classes = [], int $cols = 6, int $rows = 4) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['dash-grid'], $classes));

        $this->localStyle(['--ws-dg-cols: ' . $cols, '--ws-dg-rows: ' . $rows]);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void  {
        self::addStyle(WebshipTools::css('.dash-grid', [], 'display: grid',
            'grid-template-columns: repeat(var(--ws-dg-cols), 1fr)', 'grid-template-rows: repeat(var(--ws-dg-rows), 1fr)',
            'max-height: calc(100% - 20px)', 'column-gap: 10px', 'row-gap: 10px', 'padding: 10px 10px 10px 5px',
            'grid-auto-rows: 0', 'overflow-y: hidden', 'height: calc(100% - 20px)'));
        self::addStyle(WebshipTools::css('.dash-grid', [' > ', '*'], 'justify-self: stretch',
            'box-sizing: border-box', 'align-self: stretch', 'background: var(--ws-neutral)',
            'border-radius: 5px', 'outline: 1px solid var(--ws-dashboard-free-brd)', 'outline-offset: 1px'));
        self::addStyle(WebshipTools::css('.dash-grid', [' > ', '.filled'],
            'background: var(--ws-dashboard-filled)', 'cursor: not-allowed', 'transition: opacity 1.0s'));
        self::addStyle(WebshipTools::css('.dash-grid.progress', [' > ', '.free:not(.selectable)'],
            'cursor: not-allowed', 'opacity: 0.3'));
        self::addStyle(WebshipTools::css('.dash-grid.progress', [' > ', '.filled'],
            'opacity: 0.3'));
        self::addStyle(WebshipTools::css('.dash-grid', [' > ', '.free'], 'cursor: pointer',
            'transition: opacity 1.0s'));
        self::addStyle(WebshipTools::css('.dash-grid', [' > ', '.free', ':hover'],
            'outline-width: 5px', 'outline-color: var(--ws-dashboard-free-chosen)'));
        self::addStyle(WebshipTools::css('.dash-grid', [' > ', '.free.chosen', ':hover'],
            'outline-width: 5px'));
        self::addStyle(WebshipTools::css('.dash-grid', [' > ', '.free', '.selected'],
            'background: var(--ws-dashboard-free-chosen)'));
        self::addStyle(WebshipTools::css('.dash-grid', [' > ', '.free', '.selectable'],
            'outline-width: 3px'));
        self::addStyle(WebshipTools::css('.dash-grid', [' > ', '.free', '.selectable', ':hover'],
            'outline-width: 5px'));
        self::addStyle(WebshipTools::css('.dash-grid', [' > ', '.free', '.chosen'],
            'background: var(--ws-dashboard-free-chosen)', 'outline-width: 3px',
            'outline-color: var(--ws-dashboard-free-chosen)'));
    }

    /**
     * Create a dash-grid html view.
     * @param IDashboardItem|null $dashItem The dashboard item.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     * @param HtmlView|null $instruction1 A view that has the instruction.
     * @param HtmlView|null $instruction2 A view that has the second instruction.
     * @param HtmlView|null $container
     * @return DashGridHtmlView
     */
    public static function create(?IDashboardItem $dashItem = null, int $cols = 6, int $rows = 4,
                                  ?HtmlView $instruction1 = null, ?HtmlView $instruction2 = null,
                                  ?HtmlView $container = null) : DashGridHtmlView {
        $view = new DashGridHtmlView([], $cols, $rows);

        // Determine the map
        $map = array_fill(0, $rows, array_fill(0, $cols, ['kind' => self::FREE]));

        $dashboardHandler = DashboardHtmlView::getDashboardHandler();
        if (!is_null($dashboardHandler)) {
            $items = $dashboardHandler->getDashboardItems();
        } else $items = [];

        $firstFree = null;

        // All user-specific dashboard items
        if (!is_null($items)) {
            foreach ($items as $item) {
                if ($item instanceof IDashboardItem) {
                    for ($c = $item->getXPosition(); $c < $item->getXPosition() + $item->getXDimension(); $c++) {
                        for ($r = $item->getYPosition(); $r < $item->getYPosition() + $item->getYDimension(); $r++) {
                            if (is_null($dashItem) || ShipperRegister::getKey($item) !== ShipperRegister::getKey($dashItem)) {
                                $map[$r - 1][$c - 1] = ['kind' => self::FILLED, 'with' => $item];
                            } else {
                                $map[$r - 1][$c - 1] = ['kind' => self::PLACEHOLDER];
                            }
                        }
                    }
                }
            }
        }

        for ($r = 0; $r < count($map); $r++) {
            $row = $map[$r];
            for ($c = 0; $c < count($row); $c++) {
                $state = $row[$c];
                if ($state['kind'] === self::FREE && !$firstFree) {
                    $firstFree = [$c + 1, $r + 1, 1, 1];
                    if (is_null($dashItem)) $state = ['kind' => self::PLACEHOLDER];
                }
                $view->item($state, $c, $r, $map);
            }
        }

        if (!is_null($dashItem)) {
            $view->initXPos = $dashItem->getXPosition();
            $view->initYPos = $dashItem->getYPosition();
            $view->initXDim = $dashItem->getXDimension();
            $view->initYDim = $dashItem->getYDimension();
        } else if ($firstFree) {
            $view->initXPos = $firstFree[0];
            $view->initYPos = $firstFree[1];
            $view->initXDim = $firstFree[2];
            $view->initYDim = $firstFree[3];
            $map[$view->initYPos - 1][$view->initXPos - 1] = ['kind' => self::PLACEHOLDER];
        }

        $view->js('{' .
            'let f = (e) => {' .
            'let v = $(e.target);' .
            'let p = v.parent();' .
            'let t = ' . ($container ? '$("' . $container->idc() . '")' : 'p') . ';' .
            'let gp = t.parent();' .
            'let al = v.data("allowed");' .
            'gp.find("#xPosition").val(v.data("x"));' .
            'gp.find("#yPosition").val(v.data("y"));' .
            'gp.find("#xDimension").val(1);' .
            'gp.find("#yDimension").val(1);' .
            'p.addClass("progress");' .
            ($instruction1 ? '$("' . $instruction1->idc() . '").hide();' : '') .
            ($instruction2 ? '$("' . $instruction2->idc() . '").show();' : '') .
            'p.children().unbind("click", f);' .
            'p.children().removeClass(["chosen", "selected", "selectable"].join(" "));' .
            'al.forEach((d) => { ' .
            'let a = $(p).find("[data-x=" + (d[0] + 1) + "][data-y=" + (d[1] + 1) + "]");' .
            'a.addClass("selectable");' .
            'a.click(s);' .
            '});' .
            'v.addClass("selected");' .
            '};' .

            'let s = (e) => {' .
            'let v = $(e.target);' .
            'let p = v.parent();' .
            'let t = ' . ($container ? '$("' . $container->idc() . '")' : 'p') . ';' .
            'let gp = t.parent();' .
            'p.removeClass("progress");' .
            'let sel = p.find(".selected");' .
            ($instruction1 ? '$("' . $instruction1->idc() . '").show();' : '') .
            ($instruction2 ? '$("' . $instruction2->idc() . '").hide();' : '') .
            'gp.find("#xDimension").val(v.data("x") - sel.data("x") + 1);' .
            'gp.find("#yDimension").val(v.data("y") - sel.data("y") + 1);' .
            'p.children().unbind("click", s);' .
            'p.children().removeClass(["chosen", "selected", "selectable"].join(" "));' .
            'p.children(".free").each((i,v) => {' . '$(v).click(f);' . '});' .
            'for (let x = sel.data("x"); x <= v.data("x"); x++) {' .
            'for (let y = sel.data("y"); y <= v.data("y"); y++) {' .
            '$(p).find("[data-x=" + x + "][data-y=" + y + "]").addClass("chosen");' .
            '}' .
            '}' .
            '};' .

            '$("' . $view->idc() . '").children(".free").each((i,v) => {' . '$(v).click(f);' . '});' .
            '}');

        return $view;
    }

    /**
     * Create an item.
     * @param array $stateInfo The state information of the item.
     * @param int|null $colPos The x position of the item.
     * @param int|null $rowPos The y position of the item.
     * @return DashboardItemHtmlView
     */
    public function item(array $stateInfo, ?int $colPos = null, ?int $rowPos = null, ?array &$map = null) : HtmlView {
        $state = $stateInfo['kind'];
        $allowed = [];
        $cl = [];
        switch ($state) {
            case self::FILLED:
                {
                    $cl[] = 'filled';
                }
                break;
            case self::PLACEHOLDER:
                $cl[] = 'chosen';
            default:
                {
                    $cl[] = 'free';
                    $allowed[$colPos . '-' . $rowPos] = [$colPos, $rowPos];
                    for ($r = $rowPos; $r < count($map); $r++) {
                        $row = $map[$r];
                        for ($c = $colPos; $c < count($row); $c++) {
                            $cR = max($r - 1, $rowPos);
                            $cC = max($c - 1, $colPos);
                            if ($map[$r][$c]['kind'] === self::FREE || $map[$r][$c]['kind'] === self::PLACEHOLDER) {
                                if (($cR === $r || array_key_exists($c . '-' . $cR, $allowed)) &&
                                    ($cC === $c || array_key_exists($cC . '-' . $r, $allowed))) {
                                    $allowed[$c . '-' . $r] = [$c, $r];
                                }
                            }
                        }
                    }
                }
                break;
        }
        $title = null;
        if (array_key_exists('with', $stateInfo)) {
            $item = $stateInfo['with'];
            if ($item instanceof IDashboardItem) {
                $title = $item->getTitle();
            }
        }
        $view = new HtmlView($cl);
        if ($title) {
            $view->notice(self::getLanguage(), $title[self::getLanguage()]);
        }
        $view->data('x', $colPos + 1);
        $view->data('y', $rowPos + 1);
        $view->data('allowed', str_replace('"', "'", json_encode(array_values($allowed))));
        $this->add($view);
        return $view;
    }

    /**
     * @return int
     */
    public function getInitXPos() : int {
        return $this->initXPos;
    }

    /**
     * @return int
     */
    public function getInitYPos() : int {
        return $this->initYPos;
    }

    /**
     * @return int
     */
    public function getInitXDim() : int {
        return $this->initXDim;
    }

    /**
     * @return int
     */
    public function getInitYDim() : int {
        return $this->initYDim;
    }
}
?>