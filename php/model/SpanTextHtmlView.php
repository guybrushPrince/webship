<?php
/**
 * Class SpanTextHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class SpanTextHtmlView extends HtmlView {

    /**
     * The texts in an associative array.
     * @var array
     */
    protected $texts = array();

    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct($classes);
    }

    /**
     * @inheritDoc
     */
    public function add(View $view) : View { return $this; }

    /**
     * Add a text.
     * @param integer $language The language.
     * @param string $text The text.
     */
    public function addText($language, $text) {
        $this->texts[$language] = $text;
    }

    // Short cuts

    /**
     * @see SpanTextHtmlView->addText
     * @param integer $language The language.
     * @param string $text The text.
     * @return SpanTextHtmlView
     */
    public function text($language, $text) {
        $this->addText($language, Tools::maskTypical($text));
        return $this;
    }

    /**
     * Return a new text view.
     * @return SpanTextHtmlView
     */
    public static function _new() {
        return new SpanTextHtmlView();
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $code = '<span class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">';
        if (array_key_exists(TextView::$language, $this->texts)) {
            $code .= $this->texts[TextView::$language];
        }
        return $code;
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</span>';
    }
}
?>