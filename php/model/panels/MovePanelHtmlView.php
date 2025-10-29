<?php

/**
 * View MovePanelHtmlView.
 *
 * <p>A {@link PanelHtmlView} with movable, deletable, and draggable items. This view can be used for lists of
 * objects, for which the ordering of the objects can be changed.</p>
 * <p>Moving items up and down, deleting items, and dragging items cause events that can be attached either by
 * a specialization of this view or by adding the {@link Protocol} to it.</p>
 * <p>Since {@link MovePanelHtmlView} is a specialization of {@link PanelHtmlView}, the layout options can be reused.</p>
 *
 * <code></code>
 *
 *
 * @package webship
 * @subpackge php/model/panels
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.4.0
 *
 * @view
 * @examples
 */
class MovePanelHtmlView extends PanelHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    protected function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'movable';
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.panel.movable', [],
            '--drag-background-hover: rgba(0, 0, 0, 0.25)', '--drag-color-hover: var(--main-font)'));

        self::addStyle(Tools::css('.panel.movable.outside', [], 'position: relative'));
        self::addStyle(Tools::css('.panel.movable:not(.outside)', [' > ', '*'],
            'padding-left: 25px !important', 'padding-right: 50px !important'));
        self::addStyle(Tools::css('.panel.movable.outside', [' > ', '.movable', ' > ', '.drag'],
            'left: -20px', 'width: 20px'));
        self::addStyle(Tools::css('.panel.movable.outside', [' > ', '.movable', ' > ', '.move'],
            'display: none', 'width: 20px', 'height: 20px', 'right: -20px', 'left: initial',
            'background: var(--dark-system-background)', 'font-size: 20px', 'z-index: 100000'));
        self::addStyle(Tools::css('.panel.movable.outside', [' > ', '.movable', ':hover', ' > ', '.move'],
            'display: flex'));
        self::addStyle(Tools::css('.panel.movable.outside', [' > ', '.movable', ' > ', '.move.up'],
            'top: calc(50% - 30px)'));
        self::addStyle(Tools::css('.panel.movable.outside', [' > ', '.movable', ' > ', '.move.down'],
            'top: calc(50% + 10px)'));
        self::addStyle(Tools::css('.panel.movable.outside', [' > ', '.movable', ' > ', '.move.delete'],
            'top: calc(50% - 10px)'));
        self::addStyle(Tools::css('.panel.movable.outside', [' > ', '.movable', ' > ', '.move.delete:after'],
            'width: 50%', 'height: 50%'));

        self::addStyle(Tools::css('.panel.movable.not-draggable', [' > ', '*'],
            'padding-left: 0 !important'));

        self::addStyle(Tools::css('.panel.movable.mH', [' > ', '.movable'],
            'min-height: var(--mov-min-height)'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $firstSubview = null;
        foreach($this->subviews as $subview) {
            if ($subview instanceof MovePanelItemHtmlView) {
                $firstSubview = $subview;
                break;
            }
        }
        $lastSubview = null;
        for ($i = count($this->subviews) - 1; $i >= 0; $i--) {
            $subview = $this->subviews[$i];
            if ($subview instanceof MovePanelItemHtmlView) {
                $lastSubview = $subview;
                break;
            }
        }


        $firstSubview?->addClass('first');
        $lastSubview?->addClass('last');
        return parent::generateOpeningTag();
    }

    /**
     * Create a new move panel view.
     * @param string[]|string $classes Some classes.
     * @return MovePanelHtmlView
     */
    public static function createIt(array|string $classes = []) : MovePanelHtmlView {
        return new MovePanelHtmlView($classes);
    }

    //
    // Shortcuts
    //

    /**
     * Create an item.
     * @param string[]|string $classes A set of classes.
     * @param bool $hasDelete If it has the delete button.
     * @param bool $draggable If the item shall be draggable.
     * @param bool $movable If the item shall show move cursors.
     * @param bool $autoDelete If the delete button automatically removes this item.
     * @return MovePanelItemHtmlView
     */
    public function item(array|string $classes = [], bool $hasDelete = false,
                         bool $autoDelete = false, bool $draggable = false,
                         bool $movable = false) : MovePanelItemHtmlView {
        $itemView = MovePanelItemHtmlView::create($hasDelete, $draggable, $movable, $autoDelete, $classes);
        $this->add($itemView);
        return $itemView;
    }

    /**
     * Sets the move icons outside the panel.
     * @return $this
     */
    public function outside() : static {
        $this->addClass('outside');
        return $this;
    }

    /**
     * Set the panel to has no draggable children.
     * @return $this
     */
    public function isNotDraggable() : static {
        $this->addClass('not-draggable');
        return $this;
    }

    /**
     * Set a min height for each children.
     * @param int $minHeight The min height for each children.
     * @return $this
     */
    public function minHeightChildren(int $minHeight = 30) : static {
        $this->addClass('mH');
        $this->localStyle('--mov-min-height: ' . $minHeight . 'px');
        return $this;
    }
}
?>