<?php

namespace webship\menus;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View MenuEntryHtmlView.
 *
 * <p>A single menu entry in a menu.</p>
 *
 * <code>
 * $entry = MenuEntryHtmlView::create('Example Entry');
 * $entry->entry()->texts('Nested Entry');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/menuentry.png" />
 *
 * @title MenuEntry
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
 *     $entry = MenuEntryHtmlView::create('Example Entry');
 *     $entry->entry()->texts('Nested Entry');
 */
class MenuEntryHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    protected function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['menu-entry'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.menu-entry'], 'display: flex',
            'flex-wrap: wrap', 'cursor: pointer', 'font-size: 15px', 'font-weight: normal', 'align-items: center'));
        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.menu-entry', ':hover'],
            'background: var(--ws-main-menu-hvr)'));
        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.menu-entry', ':hover', ' > ', ' ul'],
            'display: flex', 'flex-wrap: wrap', 'position: absolute', 'left: 0', 'top: 35px'));
    }

    /**
     * Create a new menu entry.
     * @param string[]|string|null $texts The text of the menu entry (possibly in different languages).
     * @param string|null $img An optional image.
     * @param string[]|string $classes Some classes.
     * @return MenuEntryHtmlView
     */
    public static function create(array|string|null $texts = null, string|null $img = null,
                                  array|string $classes = []) : self {
        $entry = self::_new($classes);
        if (!is_null($texts)) $entry->texts($texts);
        return $entry;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $tag = parent::generateOpeningTag();
        return '<li ' . substr($tag, 4);
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</li>';
    }

    //
    // Shortcuts
    //

    /**
     * Create a menu entry.
     * @param string[]|string $classes A set of classes.
     * @param array|string|null $texts Optional texts.
     * @return MenuEntryHtmlView
     */
    public function entry(array|string $classes = [], array|string|null $texts = null) : MenuEntryHtmlView {
        $entry = MenuEntryHtmlView::create($texts, null, $classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Return a new menu entry html view.
     * @param string[]|string $classes Classes.
     * @return MenuEntryHtmlView
     */
    public static function _new(array|string $classes = []) : MenuEntryHtmlView {
        return new MenuEntryHtmlView($classes);
    }
}
?>