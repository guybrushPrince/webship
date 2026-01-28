<?php
/**
 * View InfoHtmlView.
 *
 * <p> An AlertHtmlView for <em> infos</em>.</p
 *
 *  <code>
 * InfoHtmlView::create([
 * Language::ENGLISH => 'Informational text.',
 * Language::GERMAN  => 'Informations-text.'],
 * true, false, null, null);
 *  </code>
 *
 * <img src="http://localhost/coast/webship/pic/alert-info.svg" />
 *
 * @package webship
 * @subpackge php/model/alerts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 */
class InfoHtmlView extends AlertHtmlView {

    /**
     * InfoHtmlView constructor.
     * @param string[]|string $classes The classes.
     */
    public function __construct(string|array $classes = []) {
        parent::__construct(array_merge(['info'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null,
                                  ?string $image = null) : AlertHtmlView {
        return parent::create($message, $screen, $fullView, $v ?: new InfoHtmlView(), $property,
            $image ? $image : '/webship/pic/alert-info.svg');
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.alert:not(.cover)', '.info',
            'border-color: var(--border-info)', 'color: var(--font-info)',
            'background-color: var(--background-info)'));

        self::addStyle(Tools::css('.alert.cover.info', [' > ', '.a-message'],
            'border-color: var(--border-info)', 'color: var(--font-info)',
            'background-color: var(--background-info)'));
    }
}
?>