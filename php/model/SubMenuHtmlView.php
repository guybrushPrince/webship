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

        // <div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from
        // <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
        $this->style(Tools::css('li' . $coreSelector, array(':after'),
            'content: ""', 'margin-left: 5px', 'background-image: url("' .
                HtmlActionProtocol::$serverURL . '/webship/pic/arrow-down-sign-to-navigate.svg")',
            'background-repeat: no-repeat', 'width: 10px', 'height: 10px', 'background-size: cover',
            'box-sizing: border-box'));

        $this->style(Tools::css('ul' . $coreSelector, '',
            'display: none', 'list-style-type: none', 'margin: 0', 'background: var(--main-menu-hover)',
            'padding: 0'));

        $this->style(Tools::css('ul' . $coreSelector, array(' > ', 'li'), 'width: fit-content',
            'height: 50px', 'padding: 10px 20px', 'box-sizing: border-box', 'position: relative', 'flex-basis: 100%',
            'width: -moz-fit-content'));

        $this->style(Tools::css('ul' . $coreSelector, array(' > ', 'li', ':hover'),
            'background: var(--main-menu)'));
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