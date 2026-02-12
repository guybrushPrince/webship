<?php

/**
 * Class ListItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ListItemHtmlView extends HtmlView {

    /**
     * ListItemHtmlView constructor.
     * @param string|string[] $classes The classes.
     */
    public function __construct($classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct($classes);
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<li class="' .
            implode(' ', array_merge($this->classes, array($this->id()))) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</li>';
    }

    /**
     * Creates a list entry.
     * @param bool $numbered If it should be numbered.
     * @param string[]|string $classes The classes.
     * @return ListHtmlView
     */
    public function sublist(bool $numbered = false, $classes = []) : ListHtmlView {
        return $this->add(new ListHtmlView($numbered, $classes));
    }
}
?>