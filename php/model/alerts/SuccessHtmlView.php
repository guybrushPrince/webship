<?php
/**
 * View SuccessHtmlView.
 *
 * <p> An AlertHtmlView for <em> success</em>.</p
 *
 * <code>
 * SuccessHtmlView::create([
 * Language::ENGLISH => '<h2>Successfully created user</h2>' .
 * '<p>Coast has successfully created the new user.</p>',
 * Language::GERMAN  => '<h2>Erfolgreich Nutzer angelegt</h2>' .
 * '<p>Coast hat deinen neuen Nutzer erfolgreich angelegt.</p>'
 * ], true);
 * </code>
 *
 * <img src="http://localhost/coast/webship/pic/alert-success.svg" />
 *
 * @package webship
 * @subpackge php/model/alerts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 */
class SuccessHtmlView extends AlertHtmlView {

    /**
     * SuccessHtmlView constructor.
     * @param string|string[] $classes The classes.
     */
    public function __construct(string|array $classes = []) {
        parent::__construct(array_merge(['success'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null,
                                  ?string $image = null) : AlertHtmlView {
        return parent::create($message, $screen, $fullView, $v ?: new SuccessHtmlView(), $property,
            $image);
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.alert:not(.cover)', '.success',
            'border-color: var(--border-success)', 'color: var(--font-success)',
            'background-color: var(--background-success)'));

        self::addStyle(Tools::css('.alert.cover.success', [' > ', '.a-message'],
            'border-color: var(--border-success)', 'color: var(--font-success)',
            'background-color: var(--background-success)'));

        self::addStyle(Tools::css('.alert.success', [' ', '.ctr', ' > ', 'button.std-button.closer', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(54%) sepia(4%) saturate(4282%) hue-rotate(72deg) brightness(74%) contrast(107%)'));
        self::addStyle(Tools::css('.alert.success', [' ', '.ctr', ' > ', 'button.std-button.closer:hover'],
            'background: var(--font-success)'));
        self::addStyle(Tools::css('.alert.success', [' ', '.ctr', ' > ', 'button.std-button.closer:hover', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(241deg) brightness(110%) contrast(106%)'));
    }
}
?>