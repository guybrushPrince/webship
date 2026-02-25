<?php

namespace webship\menus;

use webship\interoperability\WEvent;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View EditorMenuHtmlView.
 *
 * <p>A menu in an editor.</p>
 *
 * @package webship
 * @subpackge php/model/menus
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class EditorMenuHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'editor-menu';
        parent::__construct($classes);

        $this->ref('editor-menu');

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.editor-menu', [], 'width: 100%', 'padding: 0', 'margin: 5px 0 0 0',
            'background: var(--ws-app-brd)', 'height: 30px', 'display: flex', 'flex-direction: row'));

        self::addStyle(WebshipTools::css('.editor-menu', [' > ', '.entry'], 'font-family: Roboto, sans-serif',
            'text-transform: none', 'font-size: 12px', 'cursor: pointer', 'padding: 0 10px', 'height: 100%',
            'display: flex', 'flex-direction: row', 'align-items: center'));
        self::addStyle(WebshipTools::css('.editor-menu', [' > ', '.entry', ':hover'],
            'opacity: 1', 'background: var(--ws-main-menu)'));
        self::addStyle(WebshipTools::css('.editor-menu', [' > ', '.entry', '.active'],
            'opacity: 1', 'background: var(--ws-main-menu)', 'box-sizing: border-box',
            'text-decoration: underline', 'text-decoration-thickness: 3px', 'text-decoration-color: var(--ws-neutral)',
            'text-underline-offset: 5px'));
    }

    /**
     * Create an editor menu.
     * @param string[]|string $classes Some classes.
     * @return EditorMenuHtmlView
     */
    public static function create(array|string $classes = []) : EditorMenuHtmlView {
        return new EditorMenuHtmlView($classes);
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return str_replace('div', 'nav', parent::generateOpeningTag());
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return str_replace('div', 'nav', parent::generateClosingTag());
    }

    /**
     * Adds an entry for the menu.
     * @param string[]|string|null $texts The text(s).
     * @param string|string[] $classes Some classes.
     * @return HtmlView
     */
    public function entry(array|string|null $texts = null, array|string $classes = []) : HtmlView {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'entry';
        $entry = new HtmlView($classes);
        $this->add($entry);
        $entry->texts($texts);
        $entry->protocol(WEvent::CLICK)->
        removeClassSiblings($this, 'active', true)->
        addClass($entry, 'active');
        return $entry;
    }
}
?>