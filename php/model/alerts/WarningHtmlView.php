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