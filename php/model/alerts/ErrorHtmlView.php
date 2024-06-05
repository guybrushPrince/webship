<?php
/**
 * Class ErrorHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ErrorHtmlView extends AlertHtmlView {

    /**
     * ErrorHtmlView constructor.
     * @param string[]|string $classes The classes.
     */
    public function __construct(string|array $classes = []) {
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
    public static function create(array $message = [], bool $screen = false, ?AlertHtmlView $v = null,
                                  ?string $property = null, ?string $image = null) : AlertHtmlView {
        return parent::create($message, $screen, $v ?: new ErrorHtmlView(), $property,
            $image ? $image : '/webship/pic/alert-error.svg');
    }
}
?>