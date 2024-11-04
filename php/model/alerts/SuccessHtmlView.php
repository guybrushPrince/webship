<?php
/**
 * Class SuccessHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class SuccessHtmlView extends AlertHtmlView {

    /**
     * SuccessHtmlView constructor.
     * @param string|string[] $classes The classes.
     */
    public function __construct(string|array $classes = []) {
        parent::__construct(array_merge(['success'], $classes));

        $this->style(Tools::css('.alert:not(.cover)', '.success',
            'border-color: var(--border-success)', 'color: var(--font-success)',
            'background-color: var(--background-success)'));

        $this->style(Tools::css('.alert.cover.success', [' > ', '.a-message'],
            'border-color: var(--border-success)', 'color: var(--font-success)',
            'background-color: var(--background-success)'));

        $this->style(Tools::css('.alert.success', [' ', '.ctr', ' > ', 'button.std-button.closer', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(54%) sepia(4%) saturate(4282%) hue-rotate(72deg) brightness(74%) contrast(107%)'));
        $this->style(Tools::css('.alert.success', [' ', '.ctr', ' > ', 'button.std-button.closer:hover'],
            'background: var(--font-success)'));
        $this->style(Tools::css('.alert.success', [' ', '.ctr', ' > ', 'button.std-button.closer:hover', ' > ', 'img'],
            'filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(241deg) brightness(110%) contrast(106%)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null,
                                  ?string $image = null) : AlertHtmlView {
        return parent::create($message, $screen, $fullView, $v ?: new SuccessHtmlView(), $property,
            $image);
    }
}
?>