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

        $this->style(Tools::css('.alert', '.error',
            'border-color: rgba(235, 204, 209, 1.0)',
            'color: rgba(169, 68, 66, 1.0)', 'background-color: rgba(242, 222, 222, 1.0)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = []) : AlertHtmlView {
        $v = new ErrorHtmlView();
        $v->texts()->text(self::getLanguage(), (array_key_exists(self::getLanguage(), $message) ?
            $message[self::getLanguage()] : ''));
        return $v;
    }
}
?>