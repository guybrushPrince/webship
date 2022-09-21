<?php

/**
 * Class DashGridHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DashGridHtmlView extends HtmlView {

    const FREE = 0;
    const FILLED = 1;

    /**
     * DashGridHtmlView constructor.
     * @param string[]|string $classes Some classes.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     */
    public function __construct(array|string $classes = [], int $cols = 6, int $rows = 4) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['dash-grid'], $classes));

        $this->style(Tools::css('.dash-grid', [], 'display: grid',
            'grid-template-columns: repeat(' . $cols . ', 1fr)', 'grid-template-rows: repeat(' . $rows . ', 1fr)',
            'max-height: calc(100% - 20px)', 'column-gap: 10px', 'row-gap: 10px', 'padding: 10px 10px 10px 5px',
            'grid-auto-rows: 0', 'overflow-y: hidden', 'height: calc(100% - 20px)'));
        $this->style(Tools::css('.dash-grid', [' > ', '*'], 'justify-self: stretch',
            'box-sizing: border-box', 'align-self: stretch', 'background: rgba(200, 200, 200, 1.0)',
            'border-radius: 5px'));
        $this->style(Tools::css('.dash-grid', [' > ', '.filled'],
            'background: rgba(255, 0, 0, 0.125)'));
        $this->style(Tools::css('.dash-grid', [' > ', '.free'], 'cursor: pointer',
            'transition: background 0.5s'));
        $this->style(Tools::css('.dash-grid', [' > ', '.free', ':hover'],
            'background: rgba(0, 255, 0, 0.5)'));
        $this->style(Tools::css('.dash-grid', [' > ', '.free', '.selected'],
            'background: rgba(0, 62.5, 0, 0.5)'));
        $this->style(Tools::css('.dash-grid', [' > ', '.free', ':not(.selected).selectable'],
            'background: rgba(0, 0, 255, 0.125)'));
        $this->style(Tools::css('.dash-grid', [' > ', '.free', '.selectable', ':hover'],
            'background: rgba(0, 0, 255, 0.5)'));
        $this->style(Tools::css('.dash-grid', [' > ', '.free', ':not(:hover).chosen'],
            'background: rgba(255, 125, 0, 0.5)'));
    }

    /**
     * Create a dash-grid html view.
     * @param DashboardItem|null $dashItem The dashboard item.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     * @param HtmlView|null $instruction1 A view that has the instruction.
     * @param HtmlView|null $instruction2 A view that has the second instruction.
     * @return DashGridHtmlView
     * @throws DatabaseError
     */
    public static function create(?DashboardItem $dashItem = null, int $cols = 6, int $rows = 4,
                                  ?HtmlView $instruction1 = null, ?HtmlView $instruction2 = null) : DashGridHtmlView {
        $view = new DashGridHtmlView([], $cols, $rows);

        // Determine the map
        $map = array_fill(0, $rows, array_fill(0, $cols, self::FREE));

        // Determine the user
        $user = Persistence::instance()->getUserSession() ? Persistence::instance()->getUserSession()->getUser() : null;

        // All user-specific dashboard items
        if ($user) {
            foreach (DashboardItem::getAllWhere('user', $user) as $k => $item) {
                if ($item instanceof DashboardItem && (!$dashItem || $item->getId() !== $dashItem->getId())) {
                    for ($c = $item->getXPosition(); $c < $item->getXPosition() + $item->getXDimension(); $c++) {
                        for ($r = $item->getYPosition(); $r < $item->getYPosition() + $item->getYDimension(); $r++) {
                            $map[$r - 1][$c - 1] = self::FILLED;
                        }
                    }
                }
            }
        }

        for ($r = 0; $r < count($map); $r++) {
            $row = $map[$r];
            for ($c = 0; $c < count($row); $c++) {
                $view->item($row[$c], $c, $r, $map);
            }
        }

        $view->js('{' .
            'let f = (e) => {' .
            'let v = $(e.target);' .
            'let p = v.parent();' .
            'let gp = p.parent();' .
            'let al = v.data("allowed");' .
            'gp.find("#xPosition").val(v.data("x"));' .
            'gp.find("#yPosition").val(v.data("y"));' .
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
            'let gp = p.parent();' .
            'let sel = p.find(".selected");' .
            ($instruction1 ? '$("' . $instruction1->idc() . '").hide();' : '') .
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
     * @param int $state The state of the item.
     * @param int|null $colPos The x position of the item.
     * @param int|null $rowPos The y position of the item.
     * @return DashboardItemHtmlView
     */
    public function item(int $state, ?int $colPos = null, ?int $rowPos = null, ?array &$map = null) : HtmlView {
        $allowed = [];
        switch ($state) {
            case self::FILLED: {
                $cl = 'filled';
            } break;
            default: {
                $cl = 'free';
                $allowed[$colPos . '-' . $rowPos] = [$colPos, $rowPos];
                for ($r = $rowPos; $r < count($map); $r++) {
                    $row = $map[$r];
                    for ($c = $colPos; $c < count($row); $c++) {
                        $cR = max($r - 1, $rowPos);
                        $cC = max($c - 1, $colPos);
                        if ($map[$r][$c] === self::FREE) {
                            if (($cR === $r || array_key_exists($c . '-' . $cR, $allowed)) &&
                                ($cC === $c || array_key_exists($cC . '-' . $r, $allowed))) {
                                $allowed[$c . '-' . $r] = [$c, $r];
                            }
                        }
                    }
                }
            } break;
        }
        $view = new HtmlView($cl);
        $view->data('x', $colPos + 1);
        $view->data('y', $rowPos + 1);
        $view->data('allowed', str_replace('"', "'", json_encode(array_values($allowed))));
        $this->add($view);
        return $view;
    }
}
?>