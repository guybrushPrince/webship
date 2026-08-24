<?php

namespace webship\lists;

use webship\core\HtmlView;

/**
 * View ListItemHtmlView.
 *
 * <p>An individual list item element within a {@link ListHtmlView}.</p>
 *
 * <code>
 * $item = new ListItemHtmlView();
 * $item->texts('List item content');
 * </code>
 * 
 * <img src="http://localhost/coast/webship/modeldoc/list.png" />
 *
 * @title ListItem
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
 *     $item = new ListItemHtmlView();
 *     $item->texts('List item content');
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