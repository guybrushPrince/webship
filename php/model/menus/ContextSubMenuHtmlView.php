<?php

namespace webship\menus;

use webship\WebshipTools;

/**
 * View ContextSubMenuHtmlView.
 *
 * <p>A submenu of a context menu.</p>
 *
 * @package webship
 * @subpackge php/model/menus
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class ContextSubMenuHtmlView extends SubMenuHtmlView {

    /**
     * ContextSubMenuHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['context'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('nav.menu.context', [' ', 'li.sub-menu-entry', ':after'],
            'content: ""', 'padding-left: 5px'));
        self::addStyle(WebshipTools::css('nav.menu.context', [' ', 'li.sub-menu-entry', ' > ', 'ul'],
            'display: none', 'list-style-type: none', 'margin: 0', 'background: var(--ws-context-menu-bg)',
            'border: 1px solid var(--ws-context-menu-brd)', 'padding: 0'));
        self::addStyle(WebshipTools::css('nav.menu.context', [' ', 'li.sub-menu-entry', ' > ', 'ul', ' > ', 'li'],
            'width: fit-content',
            'min-height: 30px', 'height: auto', 'padding: 5px 20px', 'box-sizing: border-box', 'position: relative',
            'flex-basis: 100%', 'width: -moz-fit-content'));
        self::addStyle(WebshipTools::css('nav.menu.context', [' ', 'li.sub-menu-entry', ' > ', 'ul', ' > ', 'li',
            ':hover'],
            'background: var(--ws-context-menu-brd)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string|null $texts = null, array|string $classes = []) : ContextSubMenuHtmlView {
        $entry = self::_new($classes);
        if (!is_null($texts)) $entry->texts($texts);
        return $entry;
    }

    //
    // Shortcuts
    //

    /**
     * Create a context menu entry.
     * @param string[]|string $classes A set of classes.
     * @return ContextMenuEntryHtmlView
     */
    public function entry(array|string $classes = []) : ContextMenuEntryHtmlView {
        $entry = new ContextMenuEntryHtmlView($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Create a context sub menu entry.
     * @param string[]|string $classes A set of classes.
     * @return ContextSubMenuHtmlView
     */
    public function submenu(array|string $classes = []) : ContextSubMenuHtmlView {
        $entry = new ContextSubMenuHtmlView($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Return a new context sub menu html view.
     * @param string[]|string $classes Some classes.
     * @return ContextSubMenuHtmlView
     */
    public static function _new(array|string $classes = []) : ContextSubMenuHtmlView {
        return new ContextSubMenuHtmlView($classes);
    }
}
?>