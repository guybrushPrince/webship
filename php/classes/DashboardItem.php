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
     * @throws DatabaseError
     */
    public static function createDimensionEditorView(?DashboardItem $item = null, int $cols = 6, int $rows = 4,
                                                     ?HtmlView $scope = null) : HtmlView {
        if ($scope) {
            $view = $scope->add(new HtmlView('settings'));
        } else $view = new HtmlView('settings');

        $view->style(Tools::css('.settings', [' > ', '.dash-grid'], 'width: 300px',
            'height: ' . (300 * ($rows / $cols)) . 'px'));
        $view->style(Tools::css('.settings', [' ', '.hidden'], 'display: none'));

        $view->add(new FormLabelHtmlView('xDimension', 'hidden'))->texts()->
            text(Language::ENGLISH, 'Width')->
            text(Language::GERMAN,  'Breite');
        $selXDim = $view->add(new SelectHtmlView('xDimension', 'xDimension', [], true))->ref('xDimension', 'value');
        $selXDim->each(range(1, $cols), function (SelectHtmlView $select, int $col) use ($item) {
            $select->option($col, [], $item && $item->getXDimension() === $col)->
                texts()->text(View::getLanguage(), $col);
        });

        $view->add(new FormLabelHtmlView('yDimension', 'hidden'))->texts()->
            text(Language::ENGLISH, 'Height')->
            text(Language::GERMAN,  'Höhe');
        $selYDim = $view->add(new SelectHtmlView('yDimension', 'yDimension', [], true))->ref('yDimension', 'value');
        $selYDim->each(range(1, $rows), function (SelectHtmlView $select, int $row) use ($item) {
            $select->option($row, [], $item && $item->getYDimension() === $row)->
                texts()->text(View::getLanguage(), $row);
        });

        $view->add(new FormLabelHtmlView('xPosition', 'hidden'))->texts()->
            text(Language::ENGLISH, 'Position X')->
            text(Language::GERMAN,  'X-Position');
        $selXPos = $view->add(new SelectHtmlView('xPosition', 'xPosition', [], true))->ref('xPosition', 'value');
        $selXPos->each(range(1, $cols), function (SelectHtmlView $select, int $col) use ($item) {
            $select->option($col, [], $item && $item->getXPosition() === $col)->
                texts()->text(View::getLanguage(), $col);
        });

        $view->add(new FormLabelHtmlView('yPosition', 'hidden'))->texts()->
            text(Language::ENGLISH, 'Position Y')->
            text(Language::GERMAN,  'Y-Position');
        $selYPos = $view->add(new SelectHtmlView('yPosition', 'yPosition', [], true))->ref('yPosition', 'value');
        $selYPos->each(range(1, $rows), function (SelectHtmlView $select, int $row) use ($item) {
            $select->option($row, [], $item && $item->getYPosition() === $row)->
                texts()->text(View::getLanguage(), $row);
        });

        $instruction1 = $view->add(new HtmlView());
        $instruction1->ref('instruction1');
        $instruction1->texts()->
            text(Language::ENGLISH, 'Please select the desired position of the upper left corner of your dashboard item.')->
            text(Language::GERMAN,  'Bitte wählen Sie die gewünschte Position der oberen, linken Ecke Ihres Dashboard-Items.');

        $instruction2 = $view->add(new HtmlView('hidden'));
        $instruction2->ref('instruction2');
        $instruction2->texts()->
            text(Language::ENGLISH, 'Please select the desired lower right corner of your dashboard item.')->
            text(Language::GERMAN,  'Bitte wählen Sie die gewünschte untere, rechte Ecke Ihres Dashboard-Items.');

        $view->add(DashGridHtmlView::create($item, $cols, $rows, $instruction1, $instruction2));

        return $view;
    }
}
?>