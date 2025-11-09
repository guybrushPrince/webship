<?php
/**
 * Class LoadingHtmlView.
 * Presents a loading view.
 *
 * @package webship
 * @subpackage php/model
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 *
 * @view
 * @examples
 *  Usage: LoadingHtmlView::create($classes)
 *
 * @param string[]|string $classes Possible classes.
 *
 */
class LoadingHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some additional classes.
     */
    public function __construct(array|string $classes = array()) {
        parent::__construct(array_merge(['loading'], is_array($classes) ? $classes : [ $classes ]));
        self::addCSS();
    }

    /**
     * @inheritdoc
     */
    public static function addCSS() : void{
        self::addStyle(Tools::css('.loading', [], 'width: 100%', 'height: 100%',
            'background: var(--dark-system-background)', 'animation: pulse 2s infinite', 'border-radius: 6px',
            'display: flex', 'flex-direction: row', 'justify-content: center', 'align-items: center'));
        self::addStyle(Tools::css('@-webkit-keyframes pulse', [],
            '0% { background: var(--loading-dark); } ' .
            '70% { background: var(--loading-light); } ' .
            '100% { background: var(--loading-dark); }'));
        self::addStyle(Tools::css('@keyframes pulse', [],
            '0% { background: var(--loading-dark); } ' .
            '70% { background: var(--loading-light); } ' .
            '100% { background: var(--loading-dark); }'));

    }


}
?>