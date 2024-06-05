<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ImageHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ImageHtmlView extends HtmlView {

    /**
     * The image location
     * @var string
     */
    private $src;

    /**
     * ImageHtmlView constructor.
     * @param string $src The src.
     * @param string[]|string $classes Some classes
     */
    public function __construct(string $src, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['image'], $classes));
        $this->src = $src;

        $this->style(Tools::css('img.image.cover', [], 'object-fit: cover'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<img src="' . HtmlActionProtocol::$serverURL . $this->src . '" class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->notices ? ' title="' . (array_key_exists(View::getLanguage(), $this->notices) ? $this->notices[View::getLanguage()] : array_pop($this->notices)) . '"' : '') .
            '>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '';
    }

    // Short cuts

}
?>