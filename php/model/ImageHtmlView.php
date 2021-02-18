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
     * @param array|string $classes
     */
    public function __construct($src, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('image'), $classes));
        $this->src = $src;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<img src="' . HtmlActionProtocol::$serverURL . $this->src . '" class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">';
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