<?php

namespace webship\alerts;

use Tools;

/**
 * View ErrorHtmlView.
 *
 * <p>A specialization of {@link AlertHtmlView} for <em>errors</em>.</p>
 *
 * <code>
 * ErrorHtmlView::create('Error');
 * ErrorHtmlView::create('Error', true);
 * ErrorHtmlView::create('Error', true, true);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/error_1.png" /><br>
 * <img src="http://localhost/coast/webship/modeldoc/error_2.png" /><br>
 * <img src="http://localhost/coast/webship/modeldoc/error_3.png" />
 *
 * @see AlertHtmlView
 * @package webship
 * @subpackge php/model/alerts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Mihailo Jovic
 * @version 1.3.0
 *
 * @view
 * @examples
 *     ErrorHtmlView::create('Error');
 *     ErrorHtmlView::create('Error', true);
 *     ErrorHtmlView::create('Error', true, true);
 */
class ErrorHtmlView extends AlertHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes The classes.
     */
    private function __construct(array|string $classes = []) {
        parent::__construct(array_merge([ 'error' ], is_array($classes) ? $classes : [ $classes ]));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.alert.error', [], '--alert-border: var(--error-border)',
            '--alert-font: var(--error-font)', '--alert-background: var(--error-background)',
            '--alert-img-filter: brightness(0) saturate(100%) invert(36%) sepia(6%) saturate(4774%) hue-rotate(314deg) brightness(98%) contrast(99%)',
            '--alert-img-filter-hover: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(241deg) brightness(110%) contrast(106%)',
            '--alert-c-border: var(--error-font)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null,
                                  ?string $image = '/webship/pic/alert-error.svg',
                                  array|string $classes = []) : AlertHtmlView {
        return parent::create($message, $screen, $fullView, $v ?: new ErrorHtmlView($classes), $property,
            $image ? $image : '/webship/pic/alert-error.svg');
    }
}
?>