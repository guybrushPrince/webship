<?php
/**
 * Class AlertHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class AlertHtmlView extends HtmlView {

    /**
     * AlertHtmlView constructor.
     * @param array $classes The classes.
     */
    public function __construct($classes = array()) {
        parent::__construct(array_merge(['alert'], $classes));

        $this->style(Tools::css('.alert', '', 'padding: 15px', 'margin-bottom: 20px',
            'margin-top: 20px', 'border: 1px solid rgba(230, 230, 230, 1.0)', 'border-radius: 4px',
            'color: rgba(30, 30, 30, 1.0)', 'background-color: rgba(240, 240, 240, 1.0)'));
    }

    /**
     * Creates a alert html view.
     * @param array $message The multilingual messages.
     * @return AlertHtmlView
     */
    public static function create(array $message = []) : AlertHtmlView {
        $v = new AlertHtmlView();
        $v->texts()->text(self::getLanguage(), (array_key_exists(self::getLanguage(), $message) ?
            $message[self::getLanguage()] : ''));
        return $v;
    }
}
?>