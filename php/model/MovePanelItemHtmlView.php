<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class MovePanelItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class MovePanelItemHtmlView extends PanelItemHtmlView {

    /**
     * If the delete-symbol is shown.
     * @var bool
     */
    private bool $hasDelete = false;

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
    private ?IShippable $relatedObject = null;

    /**
     * MovePanelItemHtmlView constructor.
     * @param bool $hasDelete If it has the delete button.
     * @param bool $autoDelete If the delete button automatically removes this item.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(bool $hasDelete = false, bool $autoDelete = false, $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('movable'), $classes));
        $this->hasDelete = $hasDelete;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'position: relative',
            'width: 95%'));
        $this->style(Tools::css($coreSelector, ' > .move.up', 'top: 0'));
        $this->style(Tools::css($coreSelector, array(' > .move.up', ':after'), 'content: "\0025B2"',
            'width: 100%'));
        $this->style(Tools::css($coreSelector, ' > .move.down', 'top: calc(100% - 30px)'));
        $this->style(Tools::css($coreSelector, array(' > .move.down', ':after'), 'content: "\0025BC"',
            'width: 100%'));
        $this->style(Tools::css($coreSelector, ' > .move.delete', 'top: calc(50% - 15px)'));
        $this->style(Tools::css($coreSelector, array(' > .move.delete', ':after'), 'content: "X"',
            'width: 100%'));

        $this->style(Tools::css($coreSelector, [' > ', '.move-position'], 'width: 100%', 'height: 0',
            'background: var(--move-position-background)', 'border-radius: 5px',
            'border: 0 dashed var(--menu-background)', 'align-items: center', 'justify-content: center',
            'transition: 0.5s', 'display: flex'));
        $this->style(Tools::css($coreSelector, [' > ', '.move-position', ' > ', 'img'],
            'height: 0', 'opacity: 0.5', 'transition: 0.5s'));
        $this->style(Tools::css($coreSelector, ['.over.not-bottom', ' > ', '.move-position.bottom'],
            'display: none'));
        $this->style(Tools::css($coreSelector, ['.over.not-top', ' > ', '.move-position.top'],
            'display: none'));
        $this->style(Tools::css($coreSelector, ['.over:not(.in-move)', ' > ', '.move-position.hover'],
            'border: 1px solid var(--border-1)', 'opacity: 1.0', 'height: 50px'));
        $this->style(Tools::css($coreSelector, ['.over:not(.in-move)', ' > ', '.move-position.hover', ' > ', 'img'],
            'opacity: 1.0', 'height: 30px'));
        $this->style(Tools::css($coreSelector, ['.over:not(.in-move)', ' > ', '.move-position'],
            'height: 10px', 'border: 1px dashed var(--border-18)'));
        $this->style(Tools::css($coreSelector, ['.over:not(.in-move)', ' > ', '.move-position', ' > ', 'img'],
            'height: 10px'));

        $this->style(Tools::css($coreSelector, [' > ', '.drag'], 'cursor: move', 'text-align: center',
            'position: absolute', 'left: -3%', 'width: 3%', 'box-sizing: border-box', 'height: 100%',
            'transition: background 0.5s', 'display: flex', 'align-items: center', 'justify-content: center',
            'border-radius: 5px'));
        $this->style(Tools::css($coreSelector, [' > ', '.drag', ':hover'], 'background: var(--drag-background-hover)'));
        $this->style(Tools::css($coreSelector, [' > ', '.drag', ' > ', 'img'], 'opacity: 0.25', 'width: 100%'));

        $this->style(Tools::css($coreSelector, array(' > .move'), 'cursor: pointer', 'text-align: center',
            'font-size: 25px', 'color: var(--font-trans)', 'position: absolute',
            'left: 100%', 'width: 5%', 'box-sizing: border-box', 'height: 30px', 'transition: background 0.5s'));
        $this->style(Tools::css($coreSelector, array(' > .move', ':hover'), 'background: var(--drag-background-hover)',
            'color: var(--main-font)'));

        $this->style(Tools::css('.movable.outside', [' ', '.panel-item'], 'width: 100%'));
        $this->style(Tools::css('.movable.outside', [' ', '.panel-item', ' ', '.move'], 'display: none',
            'right: -15px', 'left: initial', 'background: var(--dark-system-background)', 'width: 20px', 'font-size: 20px',
            'height: 20px', 'z-index: 100000'));
        $this->style(Tools::css('.movable.outside', [' ', '.panel-item', ' ', '.move.up'],
            'top: calc(50% - 30px)'));
        $this->style(Tools::css('.movable.outside', [' ', '.panel-item', ' ', '.move.down'],
            'top: calc(50% + 10px)'));
        $this->style(Tools::css('.movable.outside', [' ', '.panel-item', ' ', '.move.delete'],
            'top: calc(50% - 10px)', 'padding: 2px', 'border-radius: 4px',
            'background: var(--delete-2)'));
        $this->style(Tools::css('.movable.outside', [' ', '.panel-item', ' ', '.move.delete', ':after'],
            'content: ""', 'background-image: url(\'' . HtmlActionProtocol::$serverURL . '/sunstone/pic/delete.svg\')',
            'background-repeat: no-repeat', 'background-origin: padding-box'));
        $this->style(Tools::css('.movable.outside', [' ', '.panel-item', ':hover', ' ', '.move'],
            'display: inherit'));
        $this->style(Tools::css('.movable.outside', [' ', '.panel-item', ' ', '.move.delete', ':hover'],
            'background: var(--delete-2-hover)'));


        $this->js('$("' . $this->idc() . '-up' . '.move.up").click(function(event) {' .
            '$(event.target).parent().insertBefore($(event.target).parent().prev());' .
            '$(event.target).trigger("' . Event::MOVEUP . '");' .
            '});');

        $this->js('$("' . $this->idc() . '-down' . '.move.down").click(function(event) {' .
            '$(event.target).parent().insertAfter($(event.target).parent().next());' .
            '$(event.target).trigger("' . Event::MOVEDOWN . '");' .
            '});');

        if ($hasDelete) {
            $this->js('$("' . $this->idc() . '-delete' . '.move.delete").click(function(event) {' .
                '$(event.target).trigger("' . Event::MOVEDELETE . '");' .
                ($autoDelete ? '$(event.target).parent().trigger("' . Event::DELETE . '").remove();' : '') .
                '});');
        }
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        if ($this->isDraggable) $this->showMoves = false;
        $key = null;
        if ($this->relatedObject) {
            $key = $this->relatedObject->getId();
            $key = (is_integer($key) ? ActionRegister::convertKey($key) : $key);
        }

        if ($this->isDraggable) {
            $this->js('drag($("' . $this->idc() . '"), $("' . $this->idc() . ' > .drag"), {related: ' . ($key !== null ? '"' . $key . '"' : "null")
                . '}, "in-move", "movable", "hover", "over", "move-position");');
        }

        if ($this->relatedObject) $this->data('related', $key);
        $open = parent::generateOpeningTag();
        if ($this->isDraggable) $open = substr($open, 0, -1) . '><div class="move-position top"><img src="' . HtmlActionProtocol::$serverURL . '/webship/pic/move-cursor.svg" alt="move-cursor"></div>';
        return $open .
            ($this->showMoves ? '<div class="' . implode(' ', array('move', 'up', $this->id() . '-up')) . '" title="' . [
                    Language::ENGLISH => 'Move this element up by one',
                    Language::GERMAN  => 'Dieses Element um eins nach oben schieben.'
                ][self::getLanguage()] . '"></div>' : '') .
            ($this->hasDelete ? '<div class="' . implode(' ', array('move', 'delete', $this->id() . '-delete')) . '" title="' . [
                    Language::ENGLISH => 'Delete this element',
                    Language::GERMAN  => 'Dieses Element löschen'
                ][self::getLanguage()] . '"></div>' : '');
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return ($this->showMoves ? '<div class="' . implode(' ', array('move', 'down', $this->id() . '-down')) . '" title="' . [
                    Language::ENGLISH => 'Move this element down by one',
                    Language::GERMAN  => 'Dieses Element um eins nach unten schieben.'
                ][self::getLanguage()] . '"></div>' : '') .
            ($this->isDraggable ? '<div class="drag" title="' . [
                    Language::ENGLISH => 'Move this element to another place',
                    Language::GERMAN  => 'Verschiebe dieses Element an eine andere Stelle'
            ][self::getLanguage()] . '"><img src="' . HtmlActionProtocol::$serverURL . '/webship/pic/drag-handle.svg" alt="move-cursor"></div>' : '') .
            ($this->isDraggable ? '<div class="move-position bottom"><img src="' . HtmlActionProtocol::$serverURL . '/webship/pic/move-cursor.svg" alt="move-cursor"></div>' : '') .
            parent::generateClosingTag();
    }

    /**
     * Disable the move keys.
     * @param bool $movable
     * @return $this
     */
    public function movable(bool $movable = true): MovePanelItemHtmlView {
        $this->showMoves = $movable;
        return $this;
    }

    /**
     * Set draggable.
     * @param bool $draggable
     * @return $this
     */
    public function draggable(bool $draggable = true): MovePanelItemHtmlView {
        $this->isDraggable = $draggable;
        return $this;
    }

    /**
     * Disable the delete keys.
     * @param bool $deletable
     * @return $this
     */
    public function deletable(bool $deletable = false): MovePanelItemHtmlView {
        $this->hasDelete = $deletable;
        return $this;
    }

    /**
     * Relate an object to this view.
     * @param IShippable|null $object The object.
     * @return void
     */
    public function relate(?IShippable $object) : void {
        $this->relatedObject = $object;
    }

}
?>