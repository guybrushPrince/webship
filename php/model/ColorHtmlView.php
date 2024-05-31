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
     * @var string|null
     */
    private ?string $title;

    /**
     * ColorHtmlView constructor.
     * @param string $color The color.
     * @param string[]|string $classes Some classes.
     * @param string|null $title An optional title.
     */
    public function __construct(string $color, array|string $classes = [], ?string $title = null) {
        parent::__construct($classes);
        $this->color = $color;
        $this->title = $title;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $opening = parent::generateOpeningTag();
        $font = Tools::getFontColor($this->color);
        return substr($opening, 0, -1) . ' style="background: ' . $this->color . '; color: ' . $font .
            ';--dev-color: ' . $this->color . '"' .
            ($this->title ? ' title="' . str_replace('"', "'", $this->title) . '"' : '')
            . '">';
    }
}
?>