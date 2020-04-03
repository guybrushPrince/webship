<?php

include_once dirname(__FILE__) . '/PanelHtmlView.php';

/**
 * Class MovePanelHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class MovePanelHtmlView extends PanelHtmlView {

    /**
     * MovePanelHtmlView constructor.
     * @param array $classes
     */
    public function __construct(array $classes = array()) {
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
     * @param array $classes A set of classes.
     * @return MovePanelItemHtmlView
     */
    public function item(array $classes = array()) {
        $itemView = new MovePanelItemHtmlView($classes);
        $this->add($itemView);
        return $itemView;
    }
}
?>