<?php

namespace webship\menus;

use webship\containers\ContainerHtmlView;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View InboxesMenuHtmlView.
 *
 * <p>A menu representing different inboxes, where each menu entry is a representative for one inbox. Each
 * inbox can have an amount of items within this box.</p>
 *
 * <code>
 * $menu = InboxesMenuHtmlView::create();
 * $menu->entry(1, true)->texts('Issues');
 * $menu->entry(133)->texts('Issues (Closed)');
 * $menu->entry(0)->texts('Comments');
 * $menu->entry(6)->texts('Comments (Read)');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/inboxesmenu.png" />
 *
 * @title InboxesMenu
 * @category menus
 * 
 * @package webship
 * @subpackage php/mode/menus
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $menu = InboxesMenuHtmlView::create();
 *     $menu->entry(1, true)->texts('Issues');
 *     $menu->entry(133)->texts('Issues (Closed)');
 *     $menu->entry(0)->texts('Comments');
 *     $menu->entry(6)->texts('Comments (Read)');
 */
class InboxesMenuHtmlView extends ContainerHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'inboxes';
        $classes = array_merge($classes, ['hor', 'hcent', 'fit', 'vcent', 'single', 'w100', 'gap20']);
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.inboxes', [' > ', '.inbox'], 'box-sizing: border-box',
            'line-height: 40px', 'cursor: pointer', 'height: 45px', 'padding-bottom: 2px'));
        self::addStyle(WebshipTools::mCss(['.inboxes', ' > ', '.inbox'], [':hover', '.active'],
            'border-bottom: 2px solid var(--ws-inbox-highlight)', 'padding-bottom: 0'));
        self::addStyle(WebshipTools::css('.inboxes', [' > ', '.inbox', '.active'],
            'font-weight: bold'));
        self::addStyle(WebshipTools::css('.inboxes', [' > ', '.inbox', ' > ',
            '.number'], 'background: var(--ws-std-bg-light)',
            'border-radius: 20px', 'min-width: 20px', 'max-height: 20px', 'font-size: 10px', 'font-weight: bold'));
    }

    /**
     * Create a new inbox menu.
     * @param string[]|string $classes Some classes.
     * @return InboxesMenuHtmlView
     */
    public static function create(array|string $classes = []) : InboxesMenuHtmlView {
        return new InboxesMenuHtmlView($classes);
    }

    /**
     * Create a menu entry.
     * @param int|null $amount The amount of the content in the inbox.
     * @param bool $active Whether this inbox is currently selected, or not.
     * @return HtmlView
     */
    public function entry(int|null $amount = null, bool $active = false) : HtmlView {
        $entry = $this->container(['inbox', 'hor', 'vcent', 'single', 'gap5']);
        if ($active) $entry->addClass('active');
        $button = $entry->view();
        if (!is_null($amount)) {
            $entry->container(['number', 'hor', 'hcent', 'vcent', 'self-fit'])->texts($amount === 0 ? '&#48;' : $amount);
        }
        return $button;
    }

}
?>