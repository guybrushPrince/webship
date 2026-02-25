<?php

namespace webship\menus;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View MenuEntryHtmlView.
 *
 * <p>A single menu entry in a menu.</p>
 *
 * @package webship
 * @subpackge php/model/menus
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class MenuEntryHtmlView extends HtmlView {

    /**
     * MenuEntryHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['menu-entry'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.menu-entry'], 'display: flex', 'flex-wrap: wrap',
            'cursor: pointer', 'font-size: 15px', 'font-weight: normal',
            'align-items: center'));
        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.menu-entry', ':hover'],
            'background: var(--ws-main-menu-hvr)'));
        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.menu-entry', ':hover', ' > ', ' ul'],
            'display: flex', 'flex-wrap: wrap', 'position: absolute', 'left: 0', 'top: 35px'));
    }

    /**
     * Create a new menu entry.
     * @param string[]|string|null $texts The text of the menu entry (possibly in different languages).
     * @param string[]|string $classes Some classes.
     * @return MenuEntryHtmlView
     */
    public static function create(array|string|null $texts = null, array|string $classes = []) : MenuEntryHtmlView {
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
     * @return MenuEntryHtmlView
     */
    public function entry(array|string $classes = []) : MenuEntryHtmlView {
        $entry = new MenuEntryHtmlView($classes);
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