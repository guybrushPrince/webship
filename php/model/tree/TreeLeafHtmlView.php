<?php

use ship\views\View;

/**
 * Class TreeLeafHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TreeLeafHtmlView extends HtmlView {

    /**
     * MenuHtmlView constructor.
     * @param string[]|string $classes
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['leaf'], $classes));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<li class="' . implode(' ', array_merge($this->classes, [$this->id()])) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</li>';
    }

    /**
     * Creates a new leaf.
     * @param string|string[] $classes Some additional classes.
     * @return TreeLeafHtmlView
     */
    public static function create(array|string $classes = []) : TreeLeafHtmlView {
        return new TreeLeafHtmlView($classes);
    }

    public function content(array|string $classes = []) : View {
        return $this->add(new HtmlView(array_merge(['leaf-info'], (is_array($classes) ? $classes : [$classes]))));
    }

}
?>