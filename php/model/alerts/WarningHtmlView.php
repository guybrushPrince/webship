<?php
/**
 * Class WarningHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class WarningHtmlView extends AlertHtmlView {

    /**
     * WarningHtmlView constructor.
     * @param string|string[] $classes The classes.
     */
    public function __construct(string|array $classes = []) {
        parent::__construct(array_merge(['warning'], $classes));

        $this->style(Tools::css('.alert:not(.cover)', '.warning',
            'border-color: var(--border-warning)', 'color: var(--font-warning)',
            'background-color: var(--background-warning)'));

        $this->style(Tools::css('.alert.cover.warning', [' > ', '.a-message'],
            'border-color: var(--border-warning)', 'color: var(--font-warning)',
            'background-color: var(--background-warning)'));

        $this->style(Tools::css('.alert.warning', [' ', '.ctr', ' > ', 'button.std-button.closer', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(43%) sepia(26%) saturate(738%) hue-rotate(359deg) brightness(96%) contrast(94%)'));
        $this->style(Tools::css('.alert.warning', [' ', '.ctr', ' > ', 'button.std-button.closer:hover'],
            'background: var(--font-warning)'));
        $this->style(Tools::css('.alert.warning', [' ', '.ctr', ' > ', 'button.std-button.closer:hover', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(241deg) brightness(110%) contrast(106%)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null,
                                  ?string $image = null) : AlertHtmlView {
        return parent::create($message, $screen, $fullView, $v ?: new WarningHtmlView(), $property,
            $image ? $image : '/webship/pic/alert-warning.svg');
    }
}
?>