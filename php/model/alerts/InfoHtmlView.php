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
            'border-color: rgba(188, 232, 241, 1.0)', 'color: rgba(49, 112, 143, 1.0)',
            'background-color: rgba(217, 237, 247, 1.0)'));

        $this->style(Tools::css('.alert.screen.info', [' > ', '.a-message'],
            'border-color: rgba(188, 232, 241, 1.0)', 'color: rgba(49, 112, 143, 1.0)',
            'background-color: rgba(217, 237, 247, 1.0)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = [], bool $screen = false, ?AlertHtmlView $v = null) : AlertHtmlView {
        return parent::create($message, $screen, $v ?: new InfoHtmlView());
    }
}
?>