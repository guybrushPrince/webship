<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ContextMenuEntryHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ContextMenuEntryHtmlView extends MenuEntryHtmlView {

    /**
     * ContextMenuEntryHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('context'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex', 'flex-wrap: wrap',
            'cursor: pointer', 'font-size: 15px', 'text-transform: none', 'font-weight: normal',
            'align-items: center'));
        $this->style(Tools::css($coreSelector, ':hover', 'background: var(--system-background)'));
        $this->style(Tools::css($coreSelector, array(':hover', ' > ', ' ul.sub-menu-entry'),
            'display: flex', 'flex-wrap: wrap', 'position: absolute', 'left: 100%', 'top: 0px'));
    }

    // Short cuts

    /**
     * Create a context menu entry.
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
     * Return a new context menu entry html view.
     * @param array|string $classes Classes.
     * @return ContextMenuEntryHtmlView
     */
    public static function _new($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new ContextMenuEntryHtmlView($classes);
    }
}
?>