<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ColorHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ColorHtmlView extends HtmlView {

    /**
     * The color.
     * @var string
     */
    private string $color;

    /**
     * An optional text to print on hover.
     * @var string
     */
    private string $title;

    /**
     * ColorHtmlView constructor.
     * @param string $color The color.
     */
    public function __construct(string $color, $classes = array(), string $title = null) {
        parent::__construct($classes);
        $this->color = $color;
        $this->title = $title;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $opening = parent::generateOpeningTag();
        return substr($opening, 0, -1) . ' style="background: ' . $this->color . '"' .
            ($this->title ? ' title="' . str_replace('"', "'", $this->title) . '"' : '')
            . '">';
    }
}
?>