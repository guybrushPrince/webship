<?php

include_once dirname(__FILE__) . '/MenuEntryHtmlView.php';

/**
 * Class SubMenuHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class SubMenuHtmlView extends MenuEntryHtmlView {

    /**
     * SubMenuHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('sub-menu-entry'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css('li' . $coreSelector, array(':after'),
            'content: ">"', 'padding-left: 5px'));

        $this->style(Tools::css('ul' . $coreSelector, '',
            'display: none', 'list-style-type: none', 'margin: 0', 'background: rgba(211, 234, 240, 1.0)',
            'padding: 0'));

        $this->style(Tools::css('ul' . $coreSelector, array(' > ', 'li'), 'width: fit-content',
            'height: 50px', 'padding: 10px 20px', 'box-sizing: border-box', 'position: relative', 'flex-basis: 100%',
            'width: -moz-fit-content'));

        $this->style(Tools::css('ul' . $coreSelector, array(' > ', 'li', ':hover'),
            'background: rgba(150, 202, 214, 1.0)'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $menuEntries = array_filter($this->subviews, function(View $view) {
            return ($view instanceof MenuEntryHtmlView);
        });
        $nonMenuEntries = array_filter($this->subviews, function(View $view) {
            return !($view instanceof MenuEntryHtmlView);
        });
        $this->subviews = $menuEntries;
        return '<li class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">' .
            implode(array_map(function (View $view) {
                return $view->generateSafe();
            }, $nonMenuEntries)) .
            '<ul class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</li></ul>';
    }



    // Short cuts

    /**
     * Create a menu entry.
     * @param array|string $classes A set of classes.
     * @return MenuEntryHtmlView
     */
    public function entry($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $entry = new MenuEntryHtmlView($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Create a sub menu entry.
     * @param array|string $classes A set of classes.
     * @return MenuEntryHtmlView
     */
    public function submenu($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $entry = new SubMenuHtmlView($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Return a new sub menu html view.
     * @param array|string $classes Classes.
     * @return SubMenuHtmlView
     */
    public static function _new($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new SubMenuHtmlView($classes);
    }
}
?>