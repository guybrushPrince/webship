<?php
/**
 * View ErrorHtmlView.
 *
 * <p> An AlertHtmlView for <em> errors</em>.</p
 *
 *  <code>
 * ErrorHtmlView::create([
 * Language::ENGLISH => '<h2>Error:</h2>
 * <p>Coast was not able to create a new user.</p>' .
 * '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>',
 * Language::GERMAN  => '<h2>Fehler:</h2> <p>Coast konnte keinen neuen Nutzer erstellen.</p>' .
 * '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>',
 * ], true);
 *  </code>
 *
 * <img src="http://localhost/coast/webship/pic/alert-error.svg" />
 *
 * @package webship
 * @subpackge php/model/alerts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 */
class ErrorHtmlView extends AlertHtmlView {

    /**
     * ErrorHtmlView constructor.
     * @param string[]|string $classes The classes.
     */
    public function __construct(string|array $classes = []) {
        parent::__construct(array_merge(['error'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null,
                                  ?string $image = '/webship/pic/alert-error.svg') : AlertHtmlView {
        return parent::create($message, $screen, $fullView, $v ?: new ErrorHtmlView(), $property,
            $image ? $image : '/webship/pic/alert-error.svg');
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.alert:not(.cover)', '.error',
            'border-color: var(--error-border)',
            'color: var(--error-font)', 'background-color: var(--error-background)'));

        self::addStyle(Tools::css('.alert.cover.error', [' > ', '.a-message'],
            'border-color: var(--error-border)',
            'color: var(--error-font)', 'background-color: var(--error-background)'));

        self::addStyle(Tools::css('.alert.error', [' ', '.ctr', ' > ', 'button.std-button.closer', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(36%) sepia(6%) saturate(4774%) hue-rotate(314deg) brightness(98%) contrast(99%)'));
        self::addStyle(Tools::css('.alert.error', [' ', '.ctr', ' > ', 'button.std-button.closer:hover'],
            'background: var(--error-font)'));
        self::addStyle(Tools::css('.alert.error', [' ', '.ctr', ' > ', 'button.std-button.closer:hover', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(241deg) brightness(110%) contrast(106%)'));
    }
}
?>