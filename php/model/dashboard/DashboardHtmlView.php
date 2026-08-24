<?php

namespace webship\dashboards;

use Error;
use webship\controller\DashboardFunctions;
use webship\interoperability\IDashboardHandler;
use webship\interoperability\IDashboardItem;
use webship\interoperability\WEvent;
use ship\interoperability\SLanguage as Language;
use webship\core\HtmlView;
use webship\dialogs\LoadingHtmlView;
use webship\symbols\PlusHtmlView;
use webship\WebshipTools;

/**
 * View DashboardHtmlView.
 *
 * <p>An initializable grid-based dashboard view that organizes items in columns and rows.</p>
 *
 * <code>
 * $dashboard = DashboardHtmlView::create(6, 4);
 * </code>
 * 
 * <img src="http://localhost/coast/webship/modeldoc/dashboard.png" />
 *
 * @title Dashboard
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
 *     $dashboard = DashboardHtmlView::create(6, 4);
 */
class DashboardHtmlView extends HtmlView {

    /**
     * The dashboard handler to manage dashboard items.
     * @var IDashboardHandler|null
     */
    private static IDashboardHandler|null $dashboardHandler = null;

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

        $this->localStyle(['--ws-dash-cols: ' . $cols, '--ws-dash-rows: ' . $rows]);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void  {
        self::addStyle(WebshipTools::css('.dashboard', [], 'display: grid',
            'grid-template-columns: repeat(var(--ws-dash-cols), 1fr)', 'grid-template-rows: repeat(var(--ws-dash-rows), 1fr)',
            'max-height: calc(100% - 20px)', 'column-gap: 10px', 'row-gap: 10px', 'padding: 10px 10px 10px 5px',
            'grid-auto-rows: 0', 'overflow-y: hidden', 'height: calc(100% - 20px)'));
        self::addStyle(WebshipTools::css('.dashboard', [' > ', '.dashboard-item'], 'justify-self: stretch',
            'overflow-y: auto', 'box-sizing: border-box', 'align-self: stretch', 'background: var(--ws-dashboard-bg)',
            'border: 0.5px solid var(--ws-dashboard-brd)', 'border-radius: 5px',
            'box-shadow: var(--ws-dashboard-shadow)', '-webkit-box-shadow: var(--ws-dashboard-shadow)',
            '-moz-box-shadow: var(--ws-dashboard-shadow-moz)', 'position: relative'));
        self::addStyle(WebshipTools::css('.dashboard', [' > ', '.dashboard-item', ':hover'],
            'border: 0.5px solid var(--ws-dashboard-item-brd-hvr)',
            'box-shadow: var(--ws-dashboard-shadow-hover)', '-webkit-box-shadow: var(--ws-dashboard-shadow-hover)',
            '-moz-box-shadow: var(--ws-dashboard-shadow-moz-hover)'));
        self::addStyle(WebshipTools::css('.dashboard', [' > ', '.dashboard-item', ':empty'], 'cursor: pointer',
            'content: "+"', 'width: 100%', 'height: 100%'));
        self::addStyle(WebshipTools::css('.dashboard', [' > ', '.dashboard-item', ':empty', ':hover'], ''
        ));

        self::addStyle(WebshipTools::css('.dashboard', [' ', '.markdown'], 'font-size: 12px',
            'overflow-y: auto'));
        self::addStyle(WebshipTools::css('.dashboard', [' ', '.markdown', ' ', 'h1'], 'font-size: 16px'));
        self::addStyle(WebshipTools::css('.dashboard', [' ', '.markdown', ' ', 'h2'], 'font-size: 14px'));
        self::addStyle(WebshipTools::css('.dashboard', [' ', '.markdown', ' ', 'h3'], 'font-size: 13px'));
        self::addStyle(WebshipTools::css('.dashboard', [' ', '.markdown', ' ', 'pre'], 'overflow-x: scroll'));

        self::addStyle(WebshipTools::css('.dashboard', [' > ', '.dashboard-item.plus', ' > ', ' .box'],
            'display: flex', 'align-items: center', 'justify-content: center', 'cursor: pointer',
            'background: var(--ws-dashboard-plus)', 'opacity: 0.5', 'transition: opacity 1s'));
        self::addStyle(WebshipTools::css('.dashboard', [' > ', '.dashboard-item.plus', ' > ', ' .box ', ' > ', '.plus'],
            'display: inline-block', 'padding: 6px', 'box-sizing: border-box',
            'background: var(--ws-plus)', 'background-position: center', 'background-size: 100% 5px, 5px 100%',
            'background-repeat: no-repeat', 'width: 50px', 'height: 50px'));
        self::addStyle(WebshipTools::css('.dashboard', [' > ', '.dashboard-item.plus', ' > ', ' .box', ':hover'],
            'opacity: 1.0'));

        self::addStyle(WebshipTools::css('.settings', [' ', '.dash-grid'], 'width: 300px',
            'height: var(--ws-dim-h)'));
        self::addStyle(WebshipTools::css('.settings', [' ', '.hidden'], 'display: none'));
    }

    /**
     * Create a dashboard html view.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     * @param bool $init Whether it is loaded or not.
     * @return DashboardHtmlView
     */
    public static function create(int $cols = 6, int $rows = 4, bool $init = true) : DashboardHtmlView {
        $view = new DashboardHtmlView([], $cols, $rows);
        $view->ref('dashboard');
        $view->protocol(WEvent::DIRTY)->doIt(DashboardFunctions::LoadDashboard, [
            'cols' => $cols,
            'rows' => $rows,
            'init' => false,
            'replace' => $view
        ], DashboardFunctions::environment());
        $isSafe = !is_null(self::$dashboardHandler) && self::$dashboardHandler->isSafe();
        if ($init) {
            if ($isSafe) {
                $view->protocol(WEvent::ONLOADCONTENT)->doIt(DashboardFunctions::LoadDashboard, [
                    'cols' => $cols,
                    'rows' => $rows,
                    'init' => false,
                    'replace' => $view
                ], DashboardFunctions::environment());
            } else {
                $view->protocol(WEvent::ONLOADCONTENT)->doIt(DashboardFunctions::LoadDashboardUnsafe, [
                    'cols' => $cols,
                    'rows' => $rows,
                    'init' => false,
                    'replace' => $view
                ], DashboardFunctions::environment());
            }
        }

        // All session-specific dashboard items
        if (!$init) {
            $items = null;
            if (!is_null(self::$dashboardHandler)) {
                $items = self::$dashboardHandler->getDashboardItems();
            }
            if (!is_null($items)) {
                $view->each($items, function (DashboardHtmlView $view, IDashboardItem $item) {
                    try {
                        $view->add($item->createView());
                    } catch (Error $error) {

                    }
                });
            } else if (!is_null(self::$dashboardHandler)) {
                $view->each(self::$dashboardHandler->getWelcomeScreen($view->cols, $view->rows), function (DashboardHtmlView $view, DashboardItemHtmlView $itemView) {
                    $view->add($itemView);
                });
            }

            $used = array_reduce($view->subviews, function (int $used, DashboardItemHtmlView $item) {
                return $used + $item->getColSpan() * $item->getRowSpan();
            }, 0);
            for ($i = $used; $i < $cols * $rows; $i++) {
                $it = $view->item();
                $it->notice(Language::ENGLISH, 'Click to add your own content');
                $it->notice(Language::GERMAN, 'Klicken, um deinen eigenen Inhalt auf das Dasbhoard zu legen');
                if ($isSafe) {
                    $it->access('survey-menu')->
                        protocol(WEvent::CLICK)->
                        mousePosition('dashboard-menu')->
                        addClass('dashboard-menu', 'active');
                }
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
    public function plus(array|string $classes = []): DashboardItemHtmlView {
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
    public static function _new(array|string $classes = []): DashboardHtmlView {
        $classes = is_array($classes) ? $classes : [$classes];
        return new DashboardHtmlView($classes);
    }

    /**
     * Initializes the dashboard handler.
     * @param IDashboardHandler $dashboardHandler The dashboard handler.
     * @return void
     */
    public static function initDashboardHandler(IDashboardHandler $dashboardHandler) : void {
        self::$dashboardHandler = $dashboardHandler;
    }

    /**
     * Get the dashboard handler.
     * @return IDashboardHandler|null
     */
    public static function getDashboardHandler() : IDashboardHandler|null {
        return self::$dashboardHandler;
    }
}
?>