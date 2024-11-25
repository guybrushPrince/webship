<?php

/**
 * Class DashGridContainerHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DashGridContainerHtmlView extends HtmlView {

    /**
     * The dash view to redirect methods.
     * @var DashGridHtmlView|null
     */
    private ?DashGridHtmlView $dashView = null;

    /**
     * DashGridContainerHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['dash-grid-container'], $classes));

        $this->style(Tools::css('.dash-grid-container', [], 'display: flex', 'width: 100%',
            'flex-direction: row', 'align-items: center', 'justify-content: center', 'flex-wrap: wrap-reverse',
            'margin-top: 10px'));
        $this->style(Tools::css('.dash-grid-container', [' > ', ':nth-child(2)'], 'min-width: 150px',
            'width: 50%'));
    }

    /**
     * Create a dash-grid html view.
     * @param DashboardItem|null $dashItem The dashboard item.
     * @param int $cols The number of columns.
     * @param int $rows The number of rows.
     * @param bool $withInstructions If instructions should be displayed.
     * @return DashGridHtmlView|DashGridContainerHtmlView
     * @throws DatabaseError
     */
    public static function create(?DashboardItem $dashItem = null, int $cols = 6, int $rows = 4,
                                  bool $withInstructions = false) : DashGridHtmlView|DashGridContainerHtmlView {
        if (!$withInstructions) {
            return DashGridHtmlView::create($dashItem, $cols, $rows);
        }

        $view = new DashGridContainerHtmlView();
        $left = $view->add(new HtmlView());
        $right = $view->add(new HtmlView());

        $instruction1 = $right->add(new HtmlView());
        $instruction1->ref('instruction1');
        $instruction1->texts()->
            text(Language::ENGLISH, 'To change the position and size, please select the desired position of the upper left corner of your dashboard item in the grid.')->
            text(Language::GERMAN,  'Du kannst die Position und Größe deines Elements auf dem Dashboard verändern. Dein Element befindet sich gerade an den Plätzen der dick-umrandeten Kästchen. 
            Um die Position und Größe zu verändern, musst du zunächst die gewünschte obere linke Ecke auswählen, an der dein Element beginnen soll. Dazu klickst du auf ein freies, ungefülltes Kästchen.');

        $instruction2 = $right->add(new HtmlView('hidden'));
        $instruction2->ref('instruction2');
        $instruction2->texts()->
            text(Language::ENGLISH, 'Please select the desired lower right corner of your dashboard item to set its dimensions.')->
            text(Language::GERMAN,  'Bitte wähle nun die gewünschte untere rechte Ecke deines Elements, um seine Dimensionen festzulegen. Die möglichen Kästchen werden dir mit dickeren Rahmen angezeigt. 
            Du kannst auch auf deine obere linke Ecke klicken, um dein Element so klein wie möglich zu halten.');

        $view->dashView = DashGridHtmlView::create($dashItem, $cols, $rows, $instruction1, $instruction2, $view);
        $left->add($view->dashView);

        return $view;
    }

    /**
     * @return int
     */
    public function getInitXPos() : int {
        return $this->dashView->getInitXPos();
    }

    /**
     * @return int
     */
    public function getInitYPos() : int {
        return $this->dashView->getInitYPos();
    }

    /**
     * @return int
     */
    public function getInitXDim() : int {
        return $this->dashView->getInitXDim();
    }

    /**
     * @return int
     */
    public function getInitYDim() : int {
        return $this->dashView->getInitYDim();
    }


}
?>