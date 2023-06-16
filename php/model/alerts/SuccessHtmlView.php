<?php
/**
 * Class SuccessHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class SuccessHtmlView extends AlertHtmlView {

    /**
     * SuccessHtmlView constructor.
     * @param array $classes The classes.
     */
    public function __construct($classes = array()) {
        parent::__construct(array_merge(['success'], $classes));

        $this->style(Tools::css('.alert:not(.screen)', '.success',
            'border-color: var(--border-success)', 'color: var(--font-success)',
            'background-color: var(--background-success)'));

        $this->style(Tools::css('.alert.screen.success', [' > ', '.a-message'],
            'border-color: var(--border-success)', 'color: var(--font-success)',
            'background-color: var(--background-success)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = [], bool $screen = false, ?AlertHtmlView $v = null) : AlertHtmlView {
        return parent::create($message, $screen, $v ?: new SuccessHtmlView());
    }
}
?>