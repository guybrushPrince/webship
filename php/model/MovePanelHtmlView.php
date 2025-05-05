<?php

include_once dirname(__FILE__) . '/PanelHtmlView.php';

/**
 * Class MovePanelHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class MovePanelHtmlView extends PanelHtmlView {

    /**
     * Outside.
     * @var bool
     */
    private bool $outside = false;

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
    public function generateOpeningTag() : string {
        $firstSubview = null;
        foreach($this->subviews as $i => $subview) {
            if ($subview instanceof HtmlView && in_array('movable', $subview->classes)) {
                $firstSubview = $subview;
                break;
            }
        }
        $lastSubview = null;
        for ($i = count($this->subviews) - 1; $i >= 0; $i--) {
            $subview = $this->subviews[$i];
            if ($subview instanceof HtmlView && in_array('movable', $subview->classes)) {
                $lastSubview = $subview;
                break;
            }
        }


        if ($firstSubview) $firstSubview->addClass('first');
        if ($lastSubview) $lastSubview->addClass('last');
        if ($this->outside) $this->classes[] = 'outside';
        return parent::generateOpeningTag();
    }

    // Short cuts

    /**
     * Create an item.
     * @param bool $hasDelete Whether the item has a delete or not.
     * @param bool $autoDelete If the delete button automatically removes the item.
     * @param array $classes A set of classes.
     * @return MovePanelItemHtmlView
     */
    public function item($hasDelete = false, bool $autoDelete = false, $classes = []) : MovePanelItemHtmlView {
        $classes = is_array($classes) ? $classes : array($classes);
        $itemView = new MovePanelItemHtmlView($hasDelete, $autoDelete, $classes);
        $this->add($itemView);
        return $itemView;
    }

    /**
     * Sets the move icons outside the panel.
     * @param $outside
     */
    public function outside($outside) {
        $this->outside = $outside;
    }
}
?>