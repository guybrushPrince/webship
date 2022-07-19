<?php
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
    public abstract function createView(array|string $classes = array()) : DashboardItemHtmlView;

    /**
     * Creates a view representing an editor for the dashboard item.
     * @param DashboardItem|null $item The dashboard item.
     * @param array|string $classes Some additional classes.
     * @return HtmlView
     */
    public abstract function createEditorView(?DashboardItem $item = null, array|string $classes = array()) : HtmlView;

    /**
     * Creates an editor to define the dimensions and the position of a monitor item.
     * @param DashboardItem|null $item The dashboard item.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     * @param HtmlView|null $scope The form if available.
     * @return HtmlView
     */
    public static function createDimensionEditorView(?DashboardItem $item = null, int $cols = 6, int $rows = 4,
                                                     ?HtmlView $scope = null) : HtmlView {
        /*$class = 'grid-' . $cols . '-' . $rows;

        $view = new HtmlView('settings');
        $view->style(Tools::css('.settings', [], 'width: 300px',
            'height: ' . (300 * ($rows / $cols)) . 'px'));
        $view->style(Tools::css('.settings', [' > ', '.' . $class], 'display: grid',
            'grid-template-columns: repeat(' . $cols . ', 1fr)', 'grid-template-rows: repeat(' . $rows . ', 1fr)',
            'width: 100%', 'height: 100%', 'column-gap: 0', 'row-gap: 0', 'cursor: pointer'));
        $view->style(Tools::css('.settings', [' > ', '.' . $class, ':hover', ' ', '.square'],
            'background: rgba(255, 0, 0, 1.0)'));
        $view->style(Tools::css('.settings', [' > ', '.' . $class, ' ', '.square'], 'justify-self: stretch',
            'align-self: stretch', 'background: rgba(245, 245, 245, 1.0)', 'border-radius: 2px', 'box-sizing: border-box',
            'border: 3px solid rgba(255, 255, 255, 1.0)'));
        $view->style(Tools::css('.settings', [' > ', '.' . $class, ' ', '.square', ':hover', ' ~ ', '.square'],
            'background: rgba(245, 245, 205, 1.0)'));

        $dimensions = $view->add(new HtmlView(['dimensions', $class]));
        $dimensions->add(new InputHtmlView('columns', 'columns', 'hidden'))->ref('xDimension');
        $dimensions->add(new InputHtmlView('rows', 'rows', 'hidden'))->ref('yDimension');
        $dimensions->each(range(1, $rows), function (HtmlView $dimView, int $row) use ($cols) {
            //$r = $dimView->add(new HtmlView('row'));
            $dimView->each(range(1, $cols), function (HtmlView $dimView, int $col) use ($row) {
                $v = $dimView->add(new HtmlView(['square', 'c-' . $row . '-' . $col]));
            });
        });*/

        if ($scope) {
            $view = $scope->add(new HtmlView('settings'));
        } else $view = new HtmlView('settings');

        $view->add(new FormLabelHtmlView('xDimension'))->texts()->
            text(Language::ENGLISH, 'Width')->
            text(Language::GERMAN,  'Breite');
        $selXDim = $view->add(new SelectHtmlView('xDimension', 'xDimension'))->ref('xDimension', 'value');
        $selXDim->each(range(1, $cols), function (SelectHtmlView $select, int $col) use ($item) {
            $select->option($col, [], $item && $item->getXDimension() === $col)->
                texts()->text(View::getLanguage(), $col);
        });

        $view->add(new FormLabelHtmlView('yDimension'))->texts()->
            text(Language::ENGLISH, 'Height')->
            text(Language::GERMAN,  'Höhe');
        $selYDim = $view->add(new SelectHtmlView('yDimension', 'yDimension'))->ref('yDimension', 'value');
        $selYDim->each(range(1, $rows), function (SelectHtmlView $select, int $row) use ($item) {
            $select->option($row, [], $item && $item->getYDimension() === $row)->
                texts()->text(View::getLanguage(), $row);
        });

        $view->add(new FormLabelHtmlView('xPosition'))->texts()->
            text(Language::ENGLISH, 'Position X')->
            text(Language::GERMAN,  'X-Position');
        $selXPos = $view->add(new SelectHtmlView('xPosition', 'xPosition'))->ref('xPosition', 'value');
        $selXPos->each(range(1, $cols), function (SelectHtmlView $select, int $col) use ($item) {
            $select->option($col, [], $item && $item->getXPosition() === $col)->
                texts()->text(View::getLanguage(), $col);
        });

        $view->add(new FormLabelHtmlView('yPosition'))->texts()->
            text(Language::ENGLISH, 'Position Y')->
            text(Language::GERMAN,  'Y-Position');
        $selYPos = $view->add(new SelectHtmlView('yPosition', 'yPosition'))->ref('yPosition', 'value');
        $selYPos->each(range(1, $rows), function (SelectHtmlView $select, int $row) use ($item) {
            $select->option($row, [], $item && $item->getYPosition() === $row)->
                texts()->text(View::getLanguage(), $row);
        });

        return $view;
    }
}
?>