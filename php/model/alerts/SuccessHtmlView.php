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

        $this->style(Tools::css('.alert', '.success',
            'border-color: rgba(214, 233, 198, 1.0)', 'color: rgba(60, 118, 61, 1.0)',
            'background-color: rgba(223, 240, 216, 1.0)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $message = []) : AlertHtmlView {
        $v = new SuccessHtmlView();
        $v->texts()->text(self::getLanguage(), (array_key_exists(self::getLanguage(), $message) ?
            $message[self::getLanguage()] : ''));
        return $v;
    }
}
?>