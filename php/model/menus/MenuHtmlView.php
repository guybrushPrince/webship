<?php

/**
 * View MenuHtmlView.
 *
 * <p>A menu that is aligned (by default) on the upper border of the screen.</p>
 *
 * @package webship
 * @subpackge php/model/menus
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class MenuHtmlView extends HtmlView {

    /**
     * MenuHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('menu'), $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('nav.menu', '', 'position: fixed', 'left: 0',
            'top: 0', 'width: 100%', 'display: flex', 'flex-direction: column', 'background: var(--main-menu)',
            'z-index: 100'));
        self::addStyle(Tools::css('nav.menu', [' ', 'ul'], '', 'list-style-type: none',
            'display: flex', 'margin: 0', 'flex-wrap: nowrap', 'padding: 0'));
        self::addStyle(Tools::css('nav.menu', [' ', 'ul', ' > ', 'li'], 'width: fit-content',
            'height: 35px', 'padding: 10px 20px', 'box-sizing: border-box', 'position: relative',
            'width: -moz-fit-content'));
        self::addStyle(Tools::css('nav.menu', [' ', 'ul', ' > ', 'li:hover'],
            '-webkit-box-shadow: var(--shadow-flow-1)', '-moz-box-shadow: var(--shadow-flow-1)',
            'box-shadow: var(--shadow-flow-1)'));

        self::addStyle(Tools::css('nav.menu', [' ', '.signals-1', ' > ', '.signal:first-of-type'],
            'top: 5px', 'right: 5px'));
        self::addStyle(Tools::css('nav.menu', [' ', '.signals-2', ' > ', '.signal:first-of-type'],
            'top: 5px', 'right: 5px'));
        self::addStyle(Tools::css('nav.menu', [' ', '.signals-2', ' > ', '.signal', ' + ', '.signal'],
            'top: 5px', 'left: 5px'));
        self::addStyle(Tools::css('nav.menu', [' ', '.signal'], 'position: absolute',
            'width: 20px', 'height: 20px', 'font-size: 10px', 'background: var(--signal)',
            'border-radius: 10px', 'opacity: 0.6'));
        self::addStyle(Tools::css('nav.menu', [' ', '.signal.blue'], 'background: var(--background-info)'));
        self::addStyle(Tools::css('nav.menu', [' ', '.signal.yellow'], 'background: var(--background-warning)'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $tag = parent::generateOpeningTag();
        return '<nav ' . substr($tag, 4) . '<ul>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</ul></nav>';
    }

    /**
     * Create a new menu.
     * @param string[]|string $classes Some classes.
     * @return MenuHtmlView
     */
    public static function create(array|string $classes = []) : MenuHtmlView {
        return new MenuHtmlView($classes);
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
     * Return a new menu html view.
     * @param string[]|string $classes Some classes.
     * @return MenuHtmlView
     */
    public static function _new(array|string $classes = []) : MenuHtmlView {
        return new MenuHtmlView($classes);
    }
}
?>