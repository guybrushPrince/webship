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
        $firstPos = 1;
        foreach($this->subviews as $i => $subview) {
            if ($subview instanceof HtmlView && in_array('movable', $subview->classes)) {
                $firstPos++;
                break;
            }
        }
        $lastPos = false;
        $subview = null;
        for ($i = count($this->subviews) - 1; $i >= 0; $i--) {
            $subview = $this->subviews[$i];
            if ($subview instanceof HtmlView && in_array('movable', $subview->classes)) {
                $lastPos = count($this->subviews) - $i;
                break;
            }
        }


        if ($firstPos) {
            $this->style(Tools::css($subview->idc() . '.movable',
                array(':nth-child(' . ($firstPos) . ')', ' > ', '.move.up'),
                'display: none'));
        }
        if ($subview instanceof HtmlView && in_array('movable', $subview->classes)) {
            $this->style(Tools::css($subview->idc() . '.movable',
                array(':nth-last-child(' . $lastPos . ')', ' > ', '.move.down'),
                'display: none'));
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