<?php

namespace webship\menus;

use Exception;
use ship\views\View;
use webship\protocols\HtmlActionProtocol;
use webship\WebshipTools;

/**
 * View SubMenuHtmlView.
 *
 * <p>A submenu within a menu.</p>
 *
 * @package webship
 * @subpackge php/model/menus
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class SubMenuHtmlView extends MenuEntryHtmlView {

    /**
     * SubMenuHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['sub-menu-entry'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        // <div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from
        // <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ':after'],
            'content: ""', 'margin-left: 5px', 'background-image: url("' .
            HtmlActionProtocol::$serverURL . '/webship/pic/arrow-down-sign-to-navigate.svg")',
            'background-repeat: no-repeat', 'width: 10px', 'height: 10px', 'background-size: cover',
            'box-sizing: border-box'));

        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ' > ', 'ul'],
            'display: none', 'list-style-type: none', 'margin: 0', 'background: var(--main-menu-hover)',
            'padding: 0',
            '-webkit-box-shadow: var(--shadow-flow-4)', '-moz-box-shadow: var(--shadow-flow-4)',
            'box-shadow: var(--shadow-flow-4)', 'border-radius: 0 6px 6px 6px'));

        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ' > ', 'ul', ' > ', 'li'],
            'width: fit-content',
            'height: 50px', 'padding: 10px 20px', 'box-sizing: border-box', 'position: relative', 'flex-basis: 100%',
            'width: -moz-fit-content'));

        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ' > ', 'ul', ' > ', 'li', ':hover'],
            'background: var(--main-menu)'));

        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ' > ', 'ul', ' > ', 'li:first-child'],
            'border-radius: 0 6px 0 0'));
        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ' > ', 'ul', ' > ', 'li:last-child'],
            'border-radius: 0 0 6px 6px'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string|null $texts = null, array|string $classes = []) : MenuEntryHtmlView {
        $entry = self::_new($classes);
        if (!is_null($texts)) $entry->texts($texts);
        return $entry;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function generateOpeningTag() : string {
        $menuEntries = array_filter($this->subviews, function (View $view) {
            return ($view instanceof MenuEntryHtmlView);
        });
        $nonMenuEntries = array_filter($this->subviews, function (View $view) {
            return !($view instanceof MenuEntryHtmlView);
        });
        $this->subviews = $menuEntries;
        $tag = parent::generateOpeningTag();
        return '<li ' . substr($tag, 4) . implode(array_map(function (View $view) {
                return $view->generateSafe();
            }, $nonMenuEntries)) .
            '<ul>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</ul></li>';
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
     * Create a sub menu entry.
     * @param string[]|string $classes A set of classes.
     * @return SubMenuHtmlView
     */
    public function submenu(array|string $classes = []) : SubMenuHtmlView {
        $entry = new SubMenuHtmlView($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Return a new sub menu html view.
     * @param string[]|string $classes Some classes.
     * @return SubMenuHtmlView
     */
    public static function _new(array|string $classes = []) : SubMenuHtmlView {
        return new SubMenuHtmlView($classes);
    }
}
?>