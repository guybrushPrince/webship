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
 * <code>
 * $submenu = SubMenuHtmlView::create('Services');
 * $submenu->entry()->texts('Web Design');
 * $submenu->entry()->texts('Hosting');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/submenu.png"/>
 *
 * @title SubMenu
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
 *     $submenu = SubMenuHtmlView::create('Services');
 *     $submenu->entry()->texts('Web Design');
 *     $submenu->entry()->texts('Hosting');
 */
class SubMenuHtmlView extends MenuEntryHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    protected function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['sub-menu-entry'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ':after'],
            'content: ""', 'margin-left: 5px', 'background-image: url("' .
            HtmlActionProtocol::getServerURL() . '/webship/pic/arrow-down.svg")',
            'background-repeat: no-repeat', 'width: 10px', 'height: 10px', 'background-size: cover',
            'box-sizing: border-box'));

        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ' > ', 'ul'],
            'display: none', 'list-style-type: none', 'margin: 0', 'background: var(--ws-main-menu-hvr)',
            'padding: 0',
            '-webkit-box-shadow: var(--ws-front-shadow)', '-moz-box-shadow: var(--ws-front-shadow)',
            'box-shadow: var(--ws-front-shadow)',
            'border-radius: 0 var(--ws-main-menu-br) var(--ws-main-menu-br) var(--ws-main-menu-br)'));

        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ' > ', 'ul', ' > ', 'li'],
            'width: fit-content',
            'height: 50px', 'padding: 10px 20px', 'box-sizing: border-box', 'position: relative', 'flex-basis: 100%',
            'width: -moz-fit-content'));

        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ' > ', 'ul', ' > ', 'li', ':hover'],
            'background: var(--ws-main-menu)'));

        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ' > ', 'ul', ' > ', 'li:first-child'],
            'border-radius: 0 var(--ws-main-menu-br) 0 0'));
        self::addStyle(WebshipTools::css('nav.menu', [' ', 'li.sub-menu-entry', ' > ', 'ul', ' > ', 'li:last-child'],
            'border-radius: 0 0 var(--ws-main-menu-br) var(--ws-main-menu-br)'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string|null $texts = null, string|null $img = null,
                                  array|string $classes = []) : self {
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
     * @param string[]|string|null $texts Optional texts.
     * @return MenuEntryHtmlView
     */
    public function entry(array|string $classes = [], array|string|null $texts = null) : MenuEntryHtmlView {
        $entry = MenuEntryHtmlView::create($texts, null, $classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Create a sub menu entry.
     * @param string[]|string $classes A set of classes.
     * @param string[]|string|null $texts Optional texts.
     * @return SubMenuHtmlView
     */
    public function submenu(array|string $classes = [], array|string|null $texts = null) : SubMenuHtmlView {
        $entry = SubMenuHtmlView::create($texts, null, $classes);
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