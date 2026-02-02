<?php

namespace webship\alerts;

use Tools;

/**
 * View SuccessHtmlView.
 *
 * <p>A specialization of {@link AlertHtmlView} for <em>success information</em>.</p>
 *
 * <code>
 * SuccessHtmlView::create('You have success.');
 * SuccessHtmlView::create('You have success.', true);
 * SuccessHtmlView::create('You have success.', true, true);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/success_1.png" /><br>
 * <img src="http://localhost/coast/webship/modeldoc/success_2.png" /><br>
 * <img src="http://localhost/coast/webship/modeldoc/success_3.png" />
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
 *     SuccessHtmlView::create('You have success.');
 *     SuccessHtmlView::create('You have success.', true);
 *     SuccessHtmlView::create('You have success.', true, true);
 */
class SuccessHtmlView extends AlertHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        parent::__construct(array_merge([ 'success' ], is_array($classes) ? $classes : [ $classes ]));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.alert.success', [], '--alert-border: var(--border-success)',
            '--alert-font: var(--font-success)', '--alert-background: var(--background-success)',
            '--alert-img-filter: brightness(0) saturate(100%) invert(54%) sepia(4%) saturate(4282%) hue-rotate(72deg) brightness(74%) contrast(107%)',
            '--alert-img-filter-hover: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(241deg) brightness(110%) contrast(106%)',
            '--alert-c-border: var(--font-success)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null,
                                  ?string $image = '/webship/pic/alert-success.svg',
                                  array|string $classes = []) : AlertHtmlView {
        return parent::create($message, $screen, $fullView, $v ?: new SuccessHtmlView($classes), $property,
            $image, $classes);
    }
}
?>