<?php

namespace webship\lists;

use webship\core\HtmlView;

/**
 * View ListHtmlView.
 *
 * <p>A list view that supports numbered lists.</p>
 *
 * <code>
 * $list = new ListHtmlView(false);
 * $item = new ListItemHtmlView();
 * $list->add($item);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/list.png" />
 * 
 * @title List
 * @category lists
 * 
 * @package webship
 * @subpackage php/model/lists
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $list = new ListHtmlView(false);
 *     $item = new ListItemHtmlView();
 *     $list->add($item);
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
    public function generateOpeningTag() : string {
        return '<' . ($this->numbered ? 'ol' : 'ul') . ' class="' .
            implode(' ', array_merge($this->classes, array($this->id()))) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</' . ($this->numbered ? 'ol' : 'ul') . '>';
    }

    /**
     * Creates a list entry.
     * @param string[]|string $classes The classes.
     * @param HtmlView|null $view A view to add.
     * @return ListItemHtmlView|HtmlView
     */
    public function entry($classes = [], ?HtmlView $view = null) : ListItemHtmlView|HtmlView {
        $entry = $this->add(new ListItemHtmlView($classes));
        if ($view) {
            $entry->add($view);
            return $view;
        } else return $entry;
    }
}
?>