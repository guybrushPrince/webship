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
     * The ratio between the width and the dasbhoard
     * @type float
     * @crucial
     * @var float
     */
    protected $widthScreenRatio = 0.5;

    /**
     * The ratio between the height and the width.
     * @type float
     * @crucial
     * @var float
     */
    protected $heightWidthRatio = 1.0;

    /**
     * The class of the view to load.
     * @type string
     * @crucial
     * @length 255
     * @var string
     */
    protected $viewClass;

    /**
     * Get the width-screen-ratio.
     * @return float
     */
    public function getWidthScreenRatio() {
        return $this->widthScreenRatio;
    }

    /**
     * Set the width-screen-ratio.
     * @param float $widthScreenRatio
     */
    public function setWidthScreenRatio($widthScreenRatio) {
        $this->widthScreenRatio = $widthScreenRatio;
    }

    /**
     * Get the height-width-ratio.
     * @return float
     */
    public function getHeightWidthRatio() {
        return $this->heightWidthRatio;
    }

    /**
     * Set the height-width-ratio.
     * @param float $heightWidthRatio
     */
    public function setHeightWidthRatio($heightWidthRatio) {
        $this->heightWidthRatio = $heightWidthRatio;
    }

    /**
     * Get the class of the view to load.
     * @return string
     */
    public function getViewClass() {
        return $this->viewClass;
    }

    /**
     * Set the class of the view to load.
     * @param string $viewClass
     */
    public function setViewClass($viewClass) {
        $this->viewClass = $viewClass;
    }

    /**
     * Create a dashboard item html view from it.
     * @param array $classes (optional)
     * @return DashboardItemHtmlView
     */
    public abstract function createView(array $classes = array());

}
?>