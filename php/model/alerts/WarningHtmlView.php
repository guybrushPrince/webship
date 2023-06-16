<?php
/**
 * Class WarningHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class WarningHtmlView extends AlertHtmlView {

    /**
     * WarningHtmlView constructor.
     * @param array $classes The classes.
     */
    public function __construct($classes = array()) {
        parent::__construct(array_merge(['warning'], $classes));

        $this->style(Tools::css('.alert:not(.screen)', '.warning',
            'border-color: var(--border-warning)', 'color: var(--font-warning)',
            'background-color: var(--background-warning)'));

        $this->style(Tools::css('.alert.screen.warning', [' > ', '.a-message'],
            'border-color: var(--border-warning)', 'color: var(--font-warning)',
            'background-color: var(--background-warning)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = [], bool $screen = false, ?AlertHtmlView $v = null) : AlertHtmlView {
        return parent::create($message, $screen, $v ?: new WarningHtmlView());
    }
}
?>