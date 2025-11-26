<?php
/**
 * View LoadingHtmlView.
 *
 * <p>Presents a <em> loading</em> view with a loading animation.</p
 * <img src="http://localhost/coast/sunstone/pic/update-arrow.svg" />
 *
 * <code>
 * $loadingView = new LoadingHtmlView();
 * $loadingView = new LoadingHtmlView(['.loading','@-webkit-keyframes pulse']);
 * $loadingView = new LoadingHtmlView(['.loading','@keyframes pulse']);
 * </code>
 *
 * @package webship
 * @subpackage php/model
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 *
 * @view
 * @examples
 *     $loadingView = new LoadingHtmlView();
 *     $loadingView = new LoadingHtmlView(['.loading','@-webkit-keyframes pulse']);
 *     $loadingView = new LoadingHtmlView(['.loading','@keyframes pulse']);
 *
 */
class LoadingHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some additional classes.
     */
    public function __construct(array|string $classes = []) {
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