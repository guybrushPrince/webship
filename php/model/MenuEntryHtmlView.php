<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class MenuEntryHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class MenuEntryHtmlView extends HtmlView {

    /**
     * MenuEntryHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('menu-entry'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex', 'flex-wrap: wrap',
            'cursor: pointer', 'font-size: 15px', 'font-weight: normal',
            'align-items: center'));
        $this->style(Tools::css($coreSelector, ':hover', 'background: rgba(211, 234, 240, 1.0)'));
        $this->style(Tools::css($coreSelector, array(':hover', ' > ', ' ul.sub-menu-entry'),
            'display: flex', 'flex-wrap: wrap', 'position: absolute', 'left: 0', 'top: 35px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<li class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</li>';
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
     * Return a new menu entry html view.
     * @param array|string $classes Classes.
     * @return MenuEntryHtmlView
     */
    public static function _new($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new MenuEntryHtmlView($classes);
    }
}
?>