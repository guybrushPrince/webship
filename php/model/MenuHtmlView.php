<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class MenuHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class MenuHtmlView extends HtmlView {

    /**
     * MenuHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('menu'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css('nav' . $coreSelector, '', 'position: fixed', 'left: 0',
            'top: 0', 'width: 100%', 'display: flex', 'flex-direction: column', 'background: rgba(150, 202, 214, 1.0)',
            'z-index: 100'));
        $this->style(Tools::css('ul' . $coreSelector, '', 'list-style-type: none',
            'display: flex', 'margin: 0', 'flex-wrap: nowrap', 'padding: 0'));
        $this->style(Tools::css('ul' . $coreSelector, array(' > ', 'li'), 'width: fit-content',
            'height: 50px', 'padding: 10px 20px', 'box-sizing: border-box', 'position: relative',
            'width: -moz-fit-content'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<nav class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">' .
            '<ul class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</ul></nav>';
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
     * Return a new menu html view.
     * @param array|string $classes Classes.
     * @return MenuHtmlView
     */
    public static function _new($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new MenuHtmlView($classes);
    }
}
?>