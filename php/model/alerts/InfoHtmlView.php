<?php

namespace webship\alerts;

use webship\WebshipTools;

/**
 * View InfoHtmlView.
 *
 * <p>A specialization of {@link AlertHtmlView} for presenting <em>information</em> to users.</p
 *
 * <code>
 * InfoHtmlView::create('For your information.');
 * InfoHtmlView::create('For your information.', true);
 * InfoHtmlView::create('For your information.', true, true);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/info_1.png" /><br>
 * <img src="http://localhost/coast/webship/modeldoc/info_2.png" /><br>
 * <img src="http://localhost/coast/webship/modeldoc/info_3.png" />
 *
 * @see AlertHtmlView
 * @package webship
 * @subpackge php/model/alerts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Mihailo Jovic
 * @version 1.2.0
 *
 * @view
 * @examples
 *     InfoHtmlView::create('For your information.');
 *     InfoHtmlView::create('For your information.', true);
 *     InfoHtmlView::create('For your information.', true, true);
 */
class InfoHtmlView extends AlertHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        parent::__construct(array_merge([ 'info' ], is_array($classes) ? $classes : [ $classes ]));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.alert.info', [], '--alert-border: var(--border-info)',
            '--alert-font: var(--font-info)', '--alert-background: var(--background-info)',
            '--alert-c-border: var(--font-info)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null, ?string $image = null,
                                  array|string $classes = []) : AlertHtmlView {
        return parent::create($message, $screen, $fullView, $v ?: new InfoHtmlView($classes), $property,
            $image ? $image : '/webship/pic/alert-info.svg', $classes);
    }
}
?>