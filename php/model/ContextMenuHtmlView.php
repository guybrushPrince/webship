<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ContextMenuHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ContextMenuHtmlView extends MenuHtmlView {

    /**
     * ContextMenuHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('context'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css('nav' . $coreSelector, '', 'position: fixed', 'left: 0',
            'top: 0', 'width: fit-content', 'display: none', 'flex-direction: column', 'background: rgba(250, 250, 250, 1.0)',
            'z-index: 100', 'border: 1px solid rgba(200, 200, 200, 1.0)'));
        $this->style(Tools::css('nav' . $coreSelector, '.active', 'display: flex'));
        $this->style(Tools::css('ul' . $coreSelector, '', 'list-style-type: none',
            'display: flex', 'margin: 0', 'flex-wrap: wrap', 'padding: 0'));
        $this->style(Tools::css('ul' . $coreSelector, array(' > ', 'li'), 'width: fit-content',
            'min-height: 30px', 'height: auto', 'padding: 5px 20px', 'box-sizing: border-box', 'position: relative'));
    }

    // Short cuts

    /**
     * Create a menu entry.
     * @param array|string $classes A set of classes.
     * @return ContextMenuEntryHtmlView
     */
    public function entry($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $entry = new ContextMenuEntryHtmlView($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Create a sub menu entry.
     * @param array|string $classes A set of classes.
     * @return ContextSubMenuHtmlView
     */
    public function submenu($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $entry = new ContextSubMenuHtmlView($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Return a new context menu html view.
     * @param array|string $classes Classes.
     * @return ContextMenuHtmlView
     */
    public static function _new($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new ContextMenuHtmlView($classes);
    }
}
?>