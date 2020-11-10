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

        /*$coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'box-sizing: border-box', 'padding: 5px',
            'height: 50px', 'width: 100%', 'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));*/
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