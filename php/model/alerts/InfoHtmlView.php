<?php
/**
 * Class InfoHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class InfoHtmlView extends AlertHtmlView {

    /**
     * InfoHtmlView constructor.
     * @param string[]|string $classes The classes.
     */
    public function __construct(string|array $classes = []) {
        parent::__construct(array_merge(['info'], $classes));

        $this->style(Tools::css('.alert:not(.cover)', '.info',
            'border-color: var(--border-info)', 'color: var(--font-info)',
            'background-color: var(--background-info)'));

        $this->style(Tools::css('.alert.cover.info', [' > ', '.a-message'],
            'border-color: var(--border-info)', 'color: var(--font-info)',
            'background-color: var(--background-info)'));
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
}
?>