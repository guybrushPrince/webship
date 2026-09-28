<?php

namespace webship\dialogs;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View LoadingHtmlView.
 *
 * <p>Presents a pulsating <em>loading</em> view. It can be inserted to load content asynchronously.</p>
 * <p>Be aware that the view takes full-width and full-height.</p>
 *
 * <code>
 * $loadingView = new LoadingHtmlView();
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/loading.png" />
 *
 * @package webship
 * @subpackage php/model/dialogs
 *
 * @version 1.1.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Mihailo Jovic
 *
 * @view
 * @examples
 *     $loadingView = new LoadingHtmlView();
 */
class LoadingHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some additional classes.
     */
    public function __construct(array|string $classes = []) {
        parent::__construct(array_merge(['loading'], is_array($classes) ? $classes : [$classes]));

        self::addCSS();
    }

    /**
     * @inheritdoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.loading', [], 'width: 100%', 'height: 100%',
            'background: var(--ws-std-bg-dark)', 'animation: pulse 2s infinite', 'border-radius: 6px',
            'display: flex', 'flex-direction: row', 'justify-content: center', 'align-items: center'));
        self::addStyle(WebshipTools::css('.loading.min-height', [], 'height: 200px'));
        self::addStyle(WebshipTools::css('@-webkit-keyframes pulse', [],
            '0% { background: var(--ws-loading-start); } ' .
            '70% { background: var(--ws-loading-end); } ' .
            '100% { background: var(--ws-loading-start); }'));
        self::addStyle(WebshipTools::css('@keyframes pulse', [],
            '0% { background: var(--ws-loading-start); } ' .
            '70% { background: var(--ws-loading-end); } ' .
            '100% { background: var(--ws-loading-start); }'));
    }

    /**
     * Create a new loading html view.
     * @param string[]|string $classes Some classes.
     * @return LoadingHtmlView
     */
    public static function create(array|string $classes = []) : LoadingHtmlView {
        return new LoadingHtmlView($classes);
    }

    /**
     * Set a minimum height.
     * @return $this
     */
    public function minHeight() : static {
        $this->addClass('min-height');
        return $this;
    }
}

?>