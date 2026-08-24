<?php

namespace webship\menus;

use webship\WebshipTools;

/**
 * View ContextMenuHtmlView.
 *
 * <p>A context menu that is usually opened with a right mouse click.</p>
 *
 * <code>
 * $menu = ContextMenuHtmlView::create();
 * $menu->entry()->texts('Open');
 * $menu->entry()->texts('Save');
 * $menu->submenu()->texts('More Options')->entry()->texts('Properties');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/contextmenu.png" />
 *
 * @title ContextMenu
 * @category menus 
 * 
 * @package webship
 * @subpackage php/model/menus
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $menu = ContextMenuHtmlView::create();
 *     $menu->entry()->texts('Open');
 *     $menu->entry()->texts('Save');
 *     $menu->submenu()->texts('More Options')->entry()->texts('Properties');
 */
class ContextMenuHtmlView extends MenuHtmlView {

    /**
     * ContextMenuHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['context'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('nav.menu.context', [], 'position: fixed', 'left: 0',
            'top: 0', 'width: fit-content', 'display: none', 'flex-direction: column',
            'background: var(--ws-context-menu-bg)',
            'z-index: 100', 'border: 1px solid var(--ws-context-menu-brd)', 'width: -moz-fit-content'));
        self::addStyle(WebshipTools::css('nav.menu.context', ['.active'], 'display: flex'));
        self::addStyle(WebshipTools::css('nav.menu.context', [' ', 'ul'], 'list-style-type: none',
            'display: flex', 'margin: 0', 'flex-wrap: nowrap', 'padding: 0', 'flex-direction: column'));
        self::addStyle(WebshipTools::css('nav.menu.context', [' ', 'ul', ' > ', 'li'], 'width: inherit',
            'min-height: 30px', 'height: auto', 'padding: 5px 20px', 'box-sizing: border-box', 'position: relative',
            'width: -moz-fit-content'));
        self::addStyle(WebshipTools::css('nav.menu.context', [' ', '.disabled'], 'opacity: 0.5'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string $classes = []) : ContextMenuHtmlView {
        return new ContextMenuHtmlView($classes);
    }

    //
    // Shortcuts
    //

    /**
     * Create a menu entry.
     * @param string[]|string $classes A set of classes.
     * @return ContextMenuEntryHtmlView
     */
    public function entry(array|string $classes = []) : MenuEntryHtmlView {
        $entry = new ContextMenuEntryHtmlView($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Create a sub menu entry.
     * @param string[]|string $classes A set of classes.
     * @return ContextSubMenuHtmlView
     */
    public function submenu(array|string $classes = []) : SubMenuHtmlView {
        $entry = new ContextSubMenuHtmlView($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Return a new context menu html view.
     * @param string[]|string $classes Classes.
     * @return ContextMenuHtmlView
     */
    public static function _new(array|string $classes = []) : MenuHtmlView {
        return new ContextMenuHtmlView($classes);
    }
}
?>