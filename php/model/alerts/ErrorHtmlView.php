<?php
/**
 * Class ErrorHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ErrorHtmlView extends AlertHtmlView {

    /**
     * ErrorHtmlView constructor.
     * @param array $classes The classes.
     */
    public function __construct($classes = array()) {
        parent::__construct(array_merge(['error'], $classes));

        $this->style(Tools::css('.alert:not(.screen)', '.error',
            'border-color: var(--error-border)',
            'color: var(--error-font)', 'background-color: var(--error-background)'));

        $this->style(Tools::css('.alert.screen.error', [' > ', '.a-message'],
            'border-color: var(--error-border)',
            'color: var(--error-font)', 'background-color: var(--error-background)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = [], bool $screen = false, ?AlertHtmlView $v = null) : AlertHtmlView {
        return parent::create($message, $screen, $v ?: new ErrorHtmlView());
    }
}
?>