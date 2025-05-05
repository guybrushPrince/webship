<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class AnchorHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class AnchorHtmlView extends HtmlView {

    /**
     * The name.
     * @var string
     */
    private string $name;

    /**
     * AnchorHtmlView constructor.
     * @param string $name The name.
     */
    public function __construct(string $name, $classes = array()) {
        parent::__construct(array_merge(array('anchor'), is_array($classes) ? $classes : [$classes]));
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<a name="' . $this->name . '" class="' .
            implode(' ', array_merge($this->classes, array($this->id()))) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</a>';
    }
}
?>