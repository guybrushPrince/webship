<?php

include_once dirname(__FILE__) . '/PanelHtmlView.php';

/**
 * Class MovePanelHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class MovePanelHtmlView extends PanelHtmlView {

    /**
     * MovePanelHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('movable'), $classes));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $started = false;
        foreach($this->subviews as $i => $subview) {
            if ($subview instanceof HtmlView && in_array('movable', $subview->classes) && !$started) {
                $this->style(Tools::css('.movable.' . $this->id(),
                    array(' > :nth-child(' . ($i + 1) . ')', ' > ', '.move.up'),
                    'display: none'));
                $started = true;
            }
            if ($subview instanceof HtmlView && !in_array('movable', $subview->classes) && $started) {
                $m = count($this->subviews) - ($i) + 1;
                $this->style(Tools::css('.movable.' . $this->id(),
                    array(' > :nth-last-child(' . ($m) . ')', ' > ', '.move.down'),
                    'display: none'));
                break;
            }
        }
        return parent::generateOpeningTag();
    }

    // Short cuts

    /**
     * Create an item.
     * @param boolean $hasDelete Whether the item has a delete or not.
     * @param array|string $classes A set of classes.
     * @return MovePanelItemHtmlView
     */
    public function item($hasDelete = false, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $itemView = new MovePanelItemHtmlView($hasDelete, $classes);
        $this->add($itemView);
        return $itemView;
    }
}
?>