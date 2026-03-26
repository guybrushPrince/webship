<?php

namespace webship\contents;

use ship\views\TextView;
use ship\views\View;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View SpanTextHtmlView.
 *
 * <p></p>
 *
 * @package webship
 * @subpackge php/model/content
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.3.0
 *
 * @view
 */
class SpanTextHtmlView extends HtmlView {

    /**
     * The texts in an associative array.
     * @var string[]
     */
    protected array $texts = [];

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void  {
        self::addStyle(WebshipTools::css('span.bold', [], 'font-weight: bold'));
        self::addStyle(WebshipTools::css('span.italic', [], 'font-style: italic'));
        self::addStyle(WebshipTools::css('span.one-line', [], 'white-space: nowrap'));
    }

    /**
     * @inheritDoc
     */
    public function add(array|View $view) : View|null {
        return $this;
    }

    /**
     * Add a text.
     * @param int $language The language.
     * @param string $text The text.
     */
    public function addText(int $language, string $text) : void {
        $this->texts[$language] = $text;
    }

    /**
     * @param int $language The language.
     * @param string $text The text.
     * @return SpanTextHtmlView
     * @see SpanTextHtmlView->addText
     */
    public function text(int $language, string $text) : static {
        $this->addText($language, WebshipTools::maskTypical($text));
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function texts(array|string|null $texts = null) : static {
        if (is_null($texts)) {
            return $this;
        } else {
            if (is_string($texts)) {
                $texts = [ self::getLanguage() => $texts ];
            }
            $this->texts += $texts;
            return $this;
        }
    }

    /**
     * Return a new text view.
     * @return SpanTextHtmlView
     * @deprecated
     */
    public static function _new() : SpanTextHtmlView {
        return self::create();
    }

    /**
     * Return a new span text html.
     * @return SpanTextHtmlView
     */
    public static function create() : SpanTextHtmlView{
        return new SpanTextHtmlView();
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $code = '<span class="' . implode(' ', array_merge($this->classes, [ $this->id() ])) . '"' .
            ($this->data ? ' ' . implode(' ', array_map(function ($key, $val) {
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

    /**
     * Set the text bold.
     * @return $this
     */
    public function bold() : static {
        $this->addClass('bold');
        return $this;
    }

    /**
     * Set the text bold.
     * @return $this
     */
    public function oneLine() : static {
        $this->addClass('one-line');
        return $this;
    }
}
?>