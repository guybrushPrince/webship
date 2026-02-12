<?php

use ship\interoperability\ShippingTools;
use ship\views\TextView;
use ship\views\View;

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
     * @param int $language The language.
     * @param string $text The text.
     * @return SpanTextHtmlView
     */
    public function text(int $language, string $text) : static {
        $this->addText($language, ShippingTools::maskTypical($text));
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
    public function generateOpeningTag() : string {
        $code = '<span class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->data ? ' ' . implode(' ', array_map(function($key, $val) {
                    return 'data-' . $key . ($val !== null ? '="' . $val . '"' : '');
                }, array_keys($this->data), $this->data)) : '') .
            '>';
        if (array_key_exists(TextView::$language, $this->texts)) {
            $code .= $this->texts[TextView::$language];
        }
        return $code;
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</span>';
    }
}
?>