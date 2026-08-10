<?php

namespace webship\alerts;

use webship\WebshipTools;

/**
 * View WarningHtmlView.
 *
 * <p>A specialization of {@link AlertHtmlView} for <em>warnings</em>.</p>
 *
 * <code>
 * WarningHtmlView::create('Be aware!');
 * WarningHtmlView::create('Be aware!', true);
 * WarningHtmlView::create('Be aware!', true, true);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/warning_1.png" /><br>
 * <img src="http://localhost/coast/webship/modeldoc/warning_2.png" /><br>
 * <img src="http://localhost/coast/webship/modeldoc/warning_3.png" />
 *
 * @title Warning
 * @category alerts
 * 
 * @see AlertHtmlView
 * @package webship
 * @subpackage php/model/alerts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Mihailo Jovic
 * @version 1.2.0
 *
 * @view
 * @examples
 *     WarningHtmlView::create('Be aware!');
 *     WarningHtmlView::create('Be aware!', true);
 *     WarningHtmlView::create('Be aware!', true, true);
 */
class WarningHtmlView extends AlertHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        parent::__construct(array_merge(['warning'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.alert.warning', [], '--ws-alert-border: var(--ws-border-warning)',
            '--ws-alert-font: var(--ws-font-warning)', '--ws-alert-background: var(--ws-background-warning)',
            '--ws-alert-img-filter: brightness(0) saturate(100%) invert(43%) sepia(26%) saturate(738%) hue-rotate(359deg) brightness(96%) contrast(94%)',
            '--ws-alert-img-filter-hover: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(241deg) brightness(110%) contrast(106%)',
            '--ws-alert-c-border: var(--ws-font-warning)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string   $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $alertId = null,
                                  ?string        $image = '/webship/pic/alert-warning.svg',
                                  array|string   $classes = []) : AlertHtmlView {
        return parent::create($message, $screen, $fullView, $v ?: new WarningHtmlView($classes), $alertId,
            $image, $classes);
    }
}
?>