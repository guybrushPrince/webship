<?php

namespace webship\menus;

use webship\WebshipTools;

/**
 * View ContextMenuEntryHtmlView.
 *
 * <p>A single menu entry in a context menu.</p>
 *
 * @package webship
 * @subpackge php/model/menus
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class ContextMenuEntryHtmlView extends MenuEntryHtmlView {

    /**
     * ContextMenuEntryHtmlView constructor.
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
        self::addStyle(WebshipTools::css('nav.menu.context', [' ', 'li'], '', 'display: flex', 'flex-wrap: wrap',
            'cursor: pointer', 'font-size: 15px', 'text-transform: none', 'font-weight: normal',
            'align-items: center'));
        self::addStyle(WebshipTools::css('nav.menu.context', [' ', 'li', ':hover'],
            'background: var(--ws-std-bg)'));
        self::addStyle(WebshipTools::css('nav.menu.context', [' ', 'li', ':hover', ' > ', ' ul'],
            'display: flex', 'flex-wrap: wrap', 'position: absolute', 'left: 100%', 'top: 0px'));
    }

    /**
     * @inheritDoc
     */
    public static function create(array|string|null $texts = null,
                                  array|string      $classes = []) : ContextMenuEntryHtmlView {
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
     * Return a new context menu entry html view.
     * @param string[]|string $classes Classes.
     * @return ContextMenuEntryHtmlView
     */
    public static function _new(array|string $classes = []) : ContextMenuEntryHtmlView {
        return new ContextMenuEntryHtmlView($classes);
    }
}
?>