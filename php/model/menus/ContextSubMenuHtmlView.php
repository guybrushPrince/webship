<?php

/**
 * Class ContextSubMenuHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ContextSubMenuHtmlView extends SubMenuHtmlView {

    /**
     * ContextSubMenuHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('context'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css('li' . $coreSelector, array(':after'),
            'content: ""', 'padding-left: 5px'));

        $this->style(Tools::css('ul' . $coreSelector, '',
            'display: none', 'list-style-type: none', 'margin: 0', 'background: var(--fine-system-background)',
            'border: 1px solid var(--system-background)', 'padding: 0'));

        $this->style(Tools::css('ul' . $coreSelector, array(' > ', 'li'), 'width: fit-content',
            'min-height: 30px', 'height: auto', 'padding: 5px 20px', 'box-sizing: border-box', 'position: relative',
            'flex-basis: 100%', 'width: -moz-fit-content'));

        $this->style(Tools::css('ul' . $coreSelector, array(' > ', 'li', ':hover'),
            'background: var(--system-background)'));
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
     * Create a context sub menu entry.
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
     * Return a new context sub menu html view.
     * @param array|string $classes Classes.
     * @return ContextSubMenuHtmlView
     */
    public static function _new($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new ContextSubMenuHtmlView($classes);
    }
}
?>