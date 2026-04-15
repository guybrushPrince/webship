<?php

namespace webship\panels;

use webship\interoperability\WEvent;
use ship\interoperability\IShippable;
use ship\interoperability\ShipperRegister;
use ship\interoperability\SLanguage as Language;
use webship\protocols\HtmlActionProtocol;
use webship\WebshipTools;

/**
 * View MovePanelItemHtmlView.
 *
 * <p><b>Note:</b> This view should be only used within a {@link MovePanelHtmlView} to guarantee its layout and
 * functionality.</p>
 * <p>An item of a panel that can be moved, deleted, and dragged. All these actions are thrown as events so that
 * specializations or instances can react on the events to perform actual actions.</p>
 *
 * <code>
 * MovePanelItemHtmlView::create(true, true, false, false, ['hor', 'vcent', 'p20'])->texts('Item 1');
 * MovePanelItemHtmlView::create(true, false, true, false, ['hor', 'vcent', 'p20'])->texts('Item 2');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/movepanelitem.png" />
 *
 * @see MovePanelHtmlView
 *
 * @package webship
 * @subpackge php/model/panels
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.4.0
 *
 * @view
 * @examples
 *     MovePanelItemHtmlView::create(true, true, false, false, ['hor', 'vcent', 'p20'])->texts('Item 1');
 *     MovePanelItemHtmlView::create(true, false, true, false, ['hor', 'vcent', 'p20'])->texts('Item 2');
 *
 */
class MovePanelItemHtmlView extends PanelItemHtmlView {

    /**
     * If the delete-symbol is shown.
     * @var bool
     */
    private bool $hasDelete;

    /**
     * Show the move items.
     * @var bool
     */
    private bool $showMoves = true;

    /**
     * Show drag options.
     * @var bool
     */
    private bool $isDraggable = true;

    /**
     * Related object (for moves).
     * @var IShippable|null
     */
    private IShippable|null $relatedObject = null;

    /**
     * Constructor.
     * @param bool $hasDelete If it has the delete button.
     * @param bool $autoDelete If the delete button automatically removes this item.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(bool $hasDelete = false, bool $autoDelete = false, array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'movable';
        $classes[] = 'rel';
        parent::__construct($classes);
        $this->hasDelete = $hasDelete;

        $this->js('$("' . $this->idc() . '-up' . '.move.up").click(function(event) {' .
            '$(event.target).parent().insertBefore($(event.target).parent().prev());' .
            '$(event.target).trigger("' . WEvent::MOVEUP . '");' .
            '});');

        $this->js('$("' . $this->idc() . '-down' . '.move.down").click(function(event) {' .
            '$(event.target).parent().insertAfter($(event.target).parent().next());' .
            '$(event.target).trigger("' . WEvent::MOVEDOWN . '");' .
            '});');

        if ($hasDelete) {
            $this->js('$("' . $this->idc() . '-delete' . '.move.delete").click(function(event) {' .
                '$(event.target).trigger("' . WEvent::MOVEDELETE . '");' .
                ($autoDelete ? '$(event.target).parent().trigger("' . WEvent::DELETE . '").remove();' : '') .
                '});');
        }

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        // Move buttons
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move'], 'cursor: pointer',
            'text-align: center', 'font-size: 25px', 'color: var(--ws-main-font-trs)', 'position: absolute',
            'right: 0', 'width: 50px', 'box-sizing: border-box', 'height: 30px',
            'transition: background 0.5s', 'display: flex', 'flex-direction: row', 'align-items: center',
            'justify-content: center', 'border-radius: 3px'));
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move', ':hover'],
            'background: var(--ws-mov-pnl-hvr-bg)', 'color: var(--ws-mov-pnl-hvr-font)'));
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move', ':after'],
            'display: flex', 'flex-direction: row', 'align-items: center', 'justify-content: center',
            'width: 100%'));

        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move.up'],
            'top: 0'));
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move.up', ':after'],
            'content: "\0025B2"'));
        self::addStyle(WebshipTools::css('.panel-item.movable', ' > .move.down',
            'top: calc(100% - 30px)'));
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move.down', ':after'],
            'content: "\0025BC"'));
        self::addStyle(WebshipTools::css('.panel-item.movable', ['.first', ' > ', '.move.up'],
            'display: none !important'));
        self::addStyle(WebshipTools::css('.panel-item.movable', ['.last', ' > ', '.move.down'],
            'display: none !important'));

        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move.delete'],
            'top: calc(50% - 15px)'));
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move.delete', ':after'],
            'display: flex', 'content: ""',
            'width: 18px', 'background-image: url(\'' . HtmlActionProtocol::getServerURL() . '/webship/pic/delete.svg\')',
            'background-repeat: no-repeat', 'background-origin: padding-box', 'height: 60%'));
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move.delete:hover'],
            'background: var(--ws-del-tr)'));

        // Drag button
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.drag'], 'cursor: grab',
            'position: absolute', 'left: 0', 'width: 25px', 'box-sizing: border-box', 'height: 100%',
            'transition: background 0.5s', 'display: flex', 'align-items: center', 'justify-content: center',
            'border-radius: 5px', 'top: 0px'));
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.drag', ':hover'],
            'background: var(--ws-mov-pnl-hvr-bg)'));
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.drag', ' > ', 'img'],
            'opacity: 0.25', 'width: 100%'));

        // Move targets
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move-position'], 'width: 100%',
            'height: 0', 'background: linear-gradient(-45deg, var(--ws-neutral), var(--ws-mov-tgt-bg))',
            'background-size: 400% 400%',
            'border-radius: 5px', 'align-items: center', 'justify-content: center', 'transition: 0.5s',
            'display: flex'));
        self::addStyle(WebshipTools::css('.panel-item.movable', [' > ', '.move-position', ' > ', 'img'],
            'height: 0', 'opacity: 0.5', 'transition: 0.5s'));
        self::addStyle(WebshipTools::css('.panel-item.movable', ['.over.not-bottom', ' > ', '.move-position.bottom'],
            'display: none'));
        self::addStyle(WebshipTools::css('.panel-item.movable', ['.over.not-top', ' > ', '.move-position.top'],
            'display: none'));
        self::addStyle(WebshipTools::css('.panel-item.movable', ['.over:not(.in-move)', ' > ', '.move-position.hover'],
            'border: 1px solid var(--ws-std-brd-dark)', 'opacity: 1.0', 'height: 50px',
            'animation: gradient 15s ease infinite'));
        self::addStyle(WebshipTools::css('.panel-item.movable', ['.over:not(.in-move)', ' > ', '.move-position.hover',
            ' > ', 'img'], 'opacity: 1.0', 'height: 30px'));
        self::addStyle(WebshipTools::css('.panel-item.movable', ['.over:not(.in-move)', ' > ', '.move-position'],
            'height: 20px'));
        self::addStyle(WebshipTools::css('.panel-item.movable', ['.over:not(.in-move)', ' > ', '.move-position', ' > ',
            'img'], 'height: 20px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        if ($this->isDraggable) $this->showMoves = false;
        $key = null;
        if ($this->relatedObject) {
            $key = $this->relatedObject->getId();
            $key = (is_integer($key) ? ShipperRegister::convertKey($key) : $key);
        }

        if ($this->isDraggable) {
            $this->js('drag($("' . $this->idc() . '"), $("' . $this->idc() . ' > .drag"), {related: ' . ($key !== null ? '"' . $key . '"' : "null")
                . '}, "in-move", "movable", "hover", "over", "move-position");');
        }

        if ($this->relatedObject) $this->data('related', $key);
        $open = parent::generateOpeningTag();
        if ($this->isDraggable) {
            $open = substr($open, 0, -1) . '><div class="move-position top">' .
                '<img src="' . HtmlActionProtocol::getServerURL() . '/webship/pic/move-cursor.svg" alt="move-cursor"></div>';
        }
        if ($this->showMoves) {
            $open .= '<div class="' . implode(' ', ['move', 'up', $this->id() . '-up']) . '" title="' . [
                    Language::ENGLISH => 'Move this element up by one',
                    Language::GERMAN => 'Dieses Element um eins nach oben schieben.'
                ][self::getLanguage()] . '"></div>';
        }
        if ($this->hasDelete) {
            $open .= '<div class="' . implode(' ', ['move', 'delete', $this->id() . '-delete']) . '" title="' . [
                    Language::ENGLISH => 'Delete this element',
                    Language::GERMAN => 'Dieses Element löschen'
                ][self::getLanguage()] . '"></div>';
        }

        return $open;
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        $closing = '';
        if ($this->showMoves) {
            $closing .= '<div class="' . implode(' ', ['move', 'down', $this->id() . '-down']) . '" ' .
                'title="' . [
                    Language::ENGLISH => 'Move this element down by one',
                    Language::GERMAN => 'Dieses Element um eins nach unten schieben.'
                ][self::getLanguage()] . '"></div>';
        }
        if ($this->isDraggable) {
            $closing .= '<div class="drag" title="' . [
                    Language::ENGLISH => 'Move this element to another place',
                    Language::GERMAN => 'Verschiebe dieses Element an eine andere Stelle'
                ][self::getLanguage()] . '">' .
                '<img src="' . HtmlActionProtocol::getServerURL() . '/webship/pic/drag-handle.svg" alt="move-cursor">' .
                '</div>';
            $closing .= '<div class="move-position bottom">' .
                '<img src="' . HtmlActionProtocol::getServerURL() . '/webship/pic/move-cursor.svg" alt="move-cursor">' .
                '</div>';
        }
        return $closing . parent::generateClosingTag();
    }

    /**
     * Create a movable item.
     * @param bool $hasDelete If it has the delete button.
     * @param bool $draggable If the item shall be draggable.
     * @param bool $movable If the item shall show move cursors.
     * @param bool $autoDelete If the delete button automatically removes this item.
     * @param string[]|string $classes Some classes.
     * @return MovePanelItemHtmlView
     */
    public static function create(bool $hasDelete = false, bool $draggable = false, bool $movable = false,
                                  bool $autoDelete = false, array|string $classes = []) : MovePanelItemHtmlView {
        $view = new MovePanelItemHtmlView($hasDelete, $autoDelete, $classes);
        $view->movable($movable);
        $view->draggable($draggable);
        return $view;
    }

    /**
     * Disable the move keys.
     * @param bool $movable
     * @return $this
     */
    public function movable(bool $movable = true) : static {
        $this->showMoves = $movable;
        return $this;
    }

    /**
     * Set draggable.
     * @param bool $draggable
     * @return $this
     */
    public function draggable(bool $draggable = true) : static {
        $this->isDraggable = $draggable;
        return $this;
    }

    /**
     * Disable the delete keys.
     * @param bool $deletable
     * @return $this
     */
    public function deletable(bool $deletable = false) : static {
        $this->hasDelete = $deletable;
        return $this;
    }

    /**
     * Relate an object to this view.
     * @param IShippable|null $object The object.
     * @return $this
     */
    public function relate(IShippable|null $object) : static {
        $this->relatedObject = $object;
        return $this;
    }
}
?>