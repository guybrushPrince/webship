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
            'border-color: rgba(250, 235, 204, 1.0)', 'color: rgba(138, 109, 59, 1.0)',
            'background-color: rgba(252, 248, 227, 1.0)'));

        $this->style(Tools::css('.alert.screen.warning', [' > ', '.a-message'],
            'border-color: rgba(250, 235, 204, 1.0)', 'color: rgba(138, 109, 59, 1.0)',
            'background-color: rgba(252, 248, 227, 1.0)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = [], bool $screen = false, ?AlertHtmlView $v = null) : AlertHtmlView {
        return parent::create($message, $screen, $v ?: new WarningHtmlView());
    }
}
?>