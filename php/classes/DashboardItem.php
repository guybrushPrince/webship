<?php

namespace webship;

use CObject;
use DashboardItemPersistentTrait;
use DatabaseError;
use Language;
use ship\views\View;
use User;
use webship\core\HtmlView;
use webship\dashboards\DashboardItemHtmlView;
use webship\dashboards\DashGridContainerHtmlView;
use webship\forms\FormLabelHtmlView;
use webship\inputs\SelectHtmlView;

/**
 * Class DashboardItem
 *
 * @persistent
 * @author Dr. Thomas M. Prinz
 */
abstract class DashboardItem extends CObject {

    use DashboardItemPersistentTrait;

    /**
     * The x-grid position.
     * @type int
     * @nullable
     * @var int|null
     */
    protected ?int $xPosition = null;

    /**
     * The y-grid position.
     * @type int
     * @nullable
     * @var int|null
     */
    protected ?int $yPosition = null;

    /**
     * The x-grid dimension.
     * @type int
     * @default 1
     * @var int
     */
    protected int $xDimension = 1;

    /**
     * The y-grid dimension.
     * @type int
     * @default 1
     * @var int
     */
    protected int $yDimension = 1;

    /**
     * The user.
     * @type User
     * @crucial
     * @var User
     */
    protected $user;

    /**
     * The class of the view to load.
     * @type string
     * @crucial
     * @length 255
     * @var string
     */
    protected $viewClass;

    /**
     * Create a dashboard item html view from it.
     * @param string[]|string $classes Additional classes.
     * @return DashboardItemHtmlView
     */
    public abstract function createView(array|string $classes = []) : DashboardItemHtmlView;

    /**
     * Creates a view representing an editor for the dashboard item.
     * @param DashboardItem|null $item The dashboard item.
     * @param array|string $classes Some additional classes.
     * @return HtmlView
     */
    public abstract function createEditorView(?DashboardItem $item = null, array|string $classes = []) : HtmlView;

    /**
     * Creates an editor to define the dimensions and the position of a monitor item.
     * @param DashboardItem|null $item The dashboard item.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     * @param HtmlView|null $scope The form if available.
     * @return HtmlView
     * @throws DatabaseError
     */
    public static function createDimensionEditorView(?DashboardItem $item = null, int $cols = 6, int $rows = 4,
                                                     ?HtmlView $scope = null) : HtmlView {
        if ($scope) {
            $view = $scope->add(new HtmlView('settings'));
        } else $view = new HtmlView('settings');

        $view->localStyle('--dim-h: ' . (300 * ($rows / $cols)) . 'px');

        $dashGrid = DashGridContainerHtmlView::create($item, $cols, $rows, true);

        $view->add(FormLabelHtmlView::create('xDimension', 'hidden'))->texts()->
            text(Language::ENGLISH, 'Width')->
            text(Language::GERMAN, 'Breite');
        $selXDim = $view->add(SelectHtmlView::create('xDimension', [], true))->ref('xDimension', 'value');
        $selXDim->each(range(1, $cols), function (SelectHtmlView $select, int $col) use ($dashGrid) {
            $select->option($col, [], $dashGrid->getInitXDim() === $col)->
            texts()->text(View::getLanguage(), $col);
        });

        $view->add(FormLabelHtmlView::create('yDimension', 'hidden'))->texts()->
            text(Language::ENGLISH, 'Height')->
            text(Language::GERMAN, 'Höhe');
        $selYDim = $view->add(SelectHtmlView::create('yDimension', [], true))->ref('yDimension', 'value');
        $selYDim->each(range(1, $rows), function (SelectHtmlView $select, int $row) use ($dashGrid) {
            $select->option($row, [], $dashGrid->getInitYDim() === $row)->texts($row);
        });

        $view->add(FormLabelHtmlView::create('xPosition', 'hidden'))->texts()->
            text(Language::ENGLISH, 'Position X')->
            text(Language::GERMAN, 'X-Position');
        $selXPos = $view->add(SelectHtmlView::create('xPosition', [], true))->ref('xPosition', 'value');
        $selXPos->each(range(1, $cols), function (SelectHtmlView $select, int $col) use ($dashGrid) {
            $select->option($col, [], $dashGrid->getInitXPos() === $col)->texts($col);
        });

        $view->add(FormLabelHtmlView::create('yPosition', 'hidden'))->texts()->
            text(Language::ENGLISH, 'Position Y')->
            text(Language::GERMAN, 'Y-Position');
        $selYPos = $view->add(SelectHtmlView::create('yPosition', [], true))->ref('yPosition', 'value');
        $selYPos->each(range(1, $rows), function (SelectHtmlView $select, int $row) use ($dashGrid) {
            $select->option($row, [], $dashGrid->getInitYPos() === $row)->texts($row);
        });

        $view->add($dashGrid);

        return $view;
    }
}
?>