<?php

/**
 * Class ListHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ListHtmlView extends HtmlView {

    /**
     * If it is numbered.
     * @var bool
     */
    private bool $numbered;

    /**
     * ListHtmlView constructor.
     * @param bool $numbered If it is numbered.
     * @param string|string[] $classes The classes.
     */
    public function __construct(bool $numbered = false, $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct($classes);
        $this->numbered = $numbered;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<' . ($this->numbered ? 'ol' : 'ul') . ' class="' .
            implode(' ', array_merge($this->classes, array($this->id()))) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</' . ($this->numbered ? 'ol' : 'ul') . '>';
    }

    /**
     * Creates a list entry.
     * @param string[]|string $classes The classes.
     * @return ListItemHtmlView
     */
    public function entry($classes = []) : ListItemHtmlView {
        return $this->add(new ListItemHtmlView($classes));
    }
}
?>