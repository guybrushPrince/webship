<?php
/**
 * Class InfoHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class InfoHtmlView extends AlertHtmlView {

    /**
     * InfoHtmlView constructor.
     * @param array $classes The classes.
     */
    public function __construct($classes = array()) {
        parent::__construct(array_merge(['info'], $classes));

        $this->style(Tools::css('.alert:not(.screen)', '.info',
            'border-color: var(--border-info)', 'color: var(--font-info)',
            'background-color: var(--background-info)'));

        $this->style(Tools::css('.alert.screen.info', [' > ', '.a-message'],
            'border-color: var(--border-info)', 'color: var(--font-info)',
            'background-color: var(--background-info)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = [], bool $screen = false, ?AlertHtmlView $v = null) : AlertHtmlView {
        return parent::create($message, $screen, $v ?: new InfoHtmlView());
    }
}
?>