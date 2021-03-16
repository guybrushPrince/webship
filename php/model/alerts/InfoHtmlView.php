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

        $this->style(Tools::css('.alert', '.info',
            'border-color: rgba(188, 232, 241, 1.0)', 'color: rgba(49, 112, 143, 1.0)',
            'background-color: rgba(217, 237, 247, 1.0)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = []) : AlertHtmlView {
        $v = new InfoHtmlView();
        $v->texts()->text(self::getLanguage(), (array_key_exists(self::getLanguage(), $message) ?
            $message[self::getLanguage()] : ''));
        return $v;
    }
}
?>