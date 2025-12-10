<?php
/**
 * View WarningHtmlView.
 *
 * <p> An AlertHtmlView for <em> warnings</em>.</p
 *
 * <code>
 * WarningHtmlView::create([
 * Language::ENGLISH => 'Warning-message.',
 * Language::GERMAN  => 'Warnungs-Nachricht.'],
 * false, false, null, null);
 * </code>
 *
 * <img src="http://localhost/coast/webship/pic/alert-warning.svg" />
 *
 * @package webship
 * @subpackge php/model/alerts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 */
class WarningHtmlView extends AlertHtmlView {

    /**
     * WarningHtmlView constructor.
     * @param string|string[] $classes The classes.
     */
    public function __construct(string|array $classes = []) {
        parent::__construct(array_merge(['warning'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null,
                                  ?string $image = null) : AlertHtmlView {
        return parent::create($message, $screen, $fullView, $v ?: new WarningHtmlView(), $property,
            $image ? $image : '/webship/pic/alert-warning.svg');
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.alert:not(.cover)', '.warning',
            'border-color: var(--border-warning)', 'color: var(--font-warning)',
            'background-color: var(--background-warning)'));

        self::addStyle(Tools::css('.alert.cover.warning', [' > ', '.a-message'],
            'border-color: var(--border-warning)', 'color: var(--font-warning)',
            'background-color: var(--background-warning)'));

        self::addStyle(Tools::css('.alert.warning', [' ', '.ctr', ' > ', 'button.std-button.closer', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(43%) sepia(26%) saturate(738%) hue-rotate(359deg) brightness(96%) contrast(94%)'));
        self::addStyle(Tools::css('.alert.warning', [' ', '.ctr', ' > ', 'button.std-button.closer:hover'],
            'background: var(--font-warning)'));
        self::addStyle(Tools::css('.alert.warning', [' ', '.ctr', ' > ', 'button.std-button.closer:hover', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(241deg) brightness(110%) contrast(106%)'));
    }
}
?>