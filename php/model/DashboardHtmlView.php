<?php

/**
 * Class DashboardHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DashboardHtmlView extends HtmlView {

    /**
     * The number of columns.
     * @var int
     */
    private int $cols;

    /**
     * The number of rows.
     * @var int
     */
    private int $rows;

    /**
     * DashboardHtmlView constructor.
     * @param string[]|string $classes Some classes.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     */
    public function __construct(array|string $classes = [], int $cols = 6, int $rows = 4) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['dashboard'], $classes));
        $this->cols = $cols;
        $this->rows = $rows;

        $this->style(Tools::css('.dashboard', [], 'display: grid',
            'grid-template-columns: repeat(' . $cols . ', 1fr)', 'grid-template-rows: repeat(' . $rows . ', 1fr)',
            'max-height: calc(100% - 20px)', 'column-gap: 10px', 'row-gap: 10px', 'padding: 10px 10px 10px 5px',
            'grid-auto-rows: 0', 'overflow-y: hidden', 'height: calc(100% - 20px)'));
        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item'], 'justify-self: stretch',
            'overflow-y: auto', 'box-sizing: border-box', 'align-self: stretch', 'background: var(--dashboard-background)',
            'border: 0.5px solid var(--border-15)', 'border-radius: 5px',
            'box-shadow: var(--shadow-flow-5)', '-webkit-box-shadow: var(--shadow-flow-5)',
            '-moz-box-shadow: var(--shadow-flow-5-moz)', 'position: relative'));
        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item', ':hover'],
            'border: 0.5px solid var(--dashboard-item-border-hover)',
            'box-shadow: var(--shadow-flow-5-hover)', '-webkit-box-shadow: var(--shadow-flow-5-hover)',
            '-moz-box-shadow: var(--shadow-flow-5-moz-hover)'));
        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item', ':empty'], 'cursor: pointer',
            'content: "+"', 'width: 100%', 'height: 100%'));
        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item', ':empty', ':hover'], ''
            ));

        $this->style(Tools::css('.dashboard', [' ', '.markdown'], 'font-size: 12px',
            'overflow-y: auto'));
        $this->style(Tools::css('.dashboard', [' ', '.markdown', ' ', 'h1'], 'font-size: 16px'));
        $this->style(Tools::css('.dashboard', [' ', '.markdown', ' ', 'h2'], 'font-size: 14px'));
        $this->style(Tools::css('.dashboard', [' ', '.markdown', ' ', 'h3'], 'font-size: 13px'));
        $this->style(Tools::css('.dashboard', [' ', '.markdown', ' ', 'pre'], 'overflow-x: scroll'));

        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item.plus', ' > ', ' .box'],
            'display: flex', 'align-items: center', 'justify-content: center', 'cursor: pointer',
            'background:var(--dashboard-plus)', 'opacity: 0.5', 'transition: opacity 1s'));
        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item.plus', ' > ', ' .box ', ' > ' , '.plus'],
    'display: inline-block', 'padding: 6px', 'box-sizing: border-box',
             'background: var(--plus)', 'background-position: center', 'background-size: 100% 5px, 5px 100%',
             'background-repeat: no-repeat', 'width: 50px', 'height: 50px'));
        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item.plus', ' > ', ' .box', ':hover'],
                    'opacity: 1.0'));

        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item', ' > ', '.box', ' > ', '.closer'],
            'position: absolute', 'right: 5px', 'top: 0'));
        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item', ' > ', '.box', ' > ', '.pencil'],
            'position: absolute', 'right: 20px', 'top: -2px'));

        $this->style(Tools::css('.dashboard', [' ', '.coast-text', ' > ', '.box'],
            'padding: 0'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t1', ' > ', '.box'],
            'background: var(--coast-text-t1)'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t1', ' > ', '.box', ' ', 'span'],
            'font-size: 75px', 'letter-spacing: -10px', 'font-weight: normal', 'margin-right: 20px'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t2', ' > ', '.box'],
            'background: var(--coast-text-t2)'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t3', ' > ', '.box'],
            'background: var(--coast-text-t3)'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t4', ' > ', '.box'],
            'background: var(--coast-text-t4)'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t5', ' > ', '.box'],
            'background: var(--coast-text-t5)'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', ' > ', '.box', ' > ', 'div'],
            'font-family: Monospace', 'font-weight: bold', 'font-size: 30', 'display: flex', 'align-items: center',
            'justify-content: center', 'width: 100%', 'height: 100%', 'box-sizing: border-box'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t1', ' > ', '.box', ' > ', 'div'],
            'justify-content: center', 'text-align: center', 'letter-spacing: -2px'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t2', ' > ', '.box', ' > ', 'div'],
            'justify-content: flex-start', 'padding-left: 20px', 'font-size: 70px'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t3', ' > ', '.box', ' > ', 'div'],
            'justify-content: flex-end', 'padding-right: 20px', 'font-size: 90px'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t4', ' > ', '.box', ' > ', 'div'],
            'font-size: 100px', 'color: var(--main-title-color)'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-text', '.t5', ' > ', '.box', ' > ', 'div'],
            'font-size: 40px'));
        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item.coast-image', ' > ', '.box'],
            'display: flex', 'flex-direction: row', 'align-items: flex-end', 'justify-content: flex-end',
            'overflow: hidden', 'padding: 0', 'background: var(--main-title-color)'));
        $this->style(Tools::css('.dashboard', [' ', '.coast-image', ' > ', '.box', ' ', 'img'],
            /*'transform: scale(2)', */'transform-origin: 100% 100%'));
    }

    /**
     * Create a dashboard html view.
     * @param User|null $user The user.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     * @param bool $init Whether it is loaded or not.
     * @return DashboardHtmlView
     * @throws DatabaseError
     */
    public static function create(?User $user = null, int $cols = 6, int $rows = 4, bool $init = true) : DashboardHtmlView {
        $view = new DashboardHtmlView([], $cols, $rows);
        $view->ref('dashboard');
        $view->protocol(Event::DIRTY)->doIt('LoadDashboard', [
            'user' => $user,
            'cols' => $cols,
            'rows' => $rows,
            'init' => false,
            'replace' => $view
        ], 'monitors');
        if ($init) {
            if ($user) {
                $view->protocol(Event::ONLOADCONTENT)->doIt('LoadDashboard', [
                    'user' => $user,
                    'cols' => $cols,
                    'rows' => $rows,
                    'init' => false,
                    'replace' => $view
                ], 'monitors');
            } else {
                $view->protocol(Event::ONLOADCONTENT)->doIt('LoadDashboardUnsafe', [
                    'cols' => $cols,
                    'rows' => $rows,
                    'init' => false,
                    'replace' => $view
                ], 'monitors');
            }
        }

        // All user-specific dashboard items
        if (!$init) {
            if ($user) {
                $view->each(DashboardItem::getAllWhere('user', $user), function (DashboardHtmlView $view, DashboardItem $item) {
                    try {
                        $view->add($item->createView());
                    } catch (Error $error) {

                    }
                });
            } else {
                //DancingScript-Regular
                $item = $view->add(new DashboardItemHtmlView(['coast-text', 't1'], 1, $cols, 1, 1));
                $item->add(new HtmlView())->texts()->text(Language::GERMAN, '<p><span>Coast.</span> Deine Befragung ist bereit, erstellt zu werden.');
                $item = $view->add(new DashboardItemHtmlView('coast-image', 1, $cols, 2, 3));
                $item->add(new ImageHtmlView('/sunstone/brand/cover.png'));
            }

            // The additional dashboard sign
            /*$view->plus()->ref('dashboard-plus')->access('survey-menu')->
                protocol(Event::CLICK)->
                mousePosition('dashboard-menu')->
                addClass('dashboard-menu', 'active');*/

            $used = array_reduce($view->subviews, function(int $used, DashboardItemHtmlView $item) {
                return $used + $item->getColSpan() * $item->getRowSpan();
            }, 0);
            for ($i = $used; $i < $cols * $rows; $i++) {
                $it = $view->item();
                if ($user) $it->access('survey-menu')->
                    protocol(Event::CLICK)->
                        mousePosition('dashboard-menu')->
                        addClass('dashboard-menu', 'active');;
            }
        } else {
            $view->item([], 1, $cols, 1, $rows)->add(new LoadingHtmlView());
        }

        return $view;
    }

    // Shortcuts

    /**
     * Create a header.
     * @param string[]|string $classes A set of classes.
     * @return DashboardHeaderHtmlView
     */
    public function header(array|string $classes = []) : DashboardHeaderHtmlView {
        $headerView = new DashboardHeaderHtmlView($classes);
        $this->add($headerView);
        return $headerView;
    }

    /**
     * Create an item.
     * @param string[]|string $classes A set of classes.
     * @param int|null $colPos The x position of the item.
     * @param int $colSpan The x size of the item.
     * @param int|null $rowPos The y position of the item.
     * @param int $rowSpan The y size of the item.
     * @return DashboardItemHtmlView
     */
    public function item(array|string $classes = [], ?int $colPos = null, int $colSpan = 1, ?int $rowPos = null,
                         int $rowSpan = 1) : DashboardItemHtmlView {
        $itemView = new DashboardItemHtmlView($classes, $colPos, $colSpan, $rowPos, $rowSpan);
        $this->add($itemView);
        return $itemView;
    }

    /**
     * Add a plus dashboard item.
     * @param string[]|string $classes Additional classes.
     * @return DashboardItemHtmlView
     */
    public function plus(array|string $classes = []) : DashboardItemHtmlView {
        $itemView = new DashboardItemHtmlView(['plus'] + $classes);
        $itemView->add(new PlusHtmlView());
        $this->add($itemView);
        return $itemView;
    }

    /**
     * Return a new container html view.
     * @param string[]|string $classes Classes.
     * @return DashboardHtmlView
     */
    public static function _new(array|string $classes = []) : DashboardHtmlView {
        $classes = is_array($classes) ? $classes : [$classes];
        return new DashboardHtmlView($classes);
    }
}
?>