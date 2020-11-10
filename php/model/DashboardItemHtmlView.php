<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class DashboardItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DashboardItemHtmlView extends HtmlView {

    /**
     * DashboardItemHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('dashboard-item'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex', 'flex-wrap: wrap',
            'padding: 5px'));

        $this->style(Tools::css($coreSelector, array(' > ', '.box', ' > ',  '*'), 'box-sizing: border-box',
            'padding: 10px'));

        $this->style(Tools::css($coreSelector, array(' > ', '.box'), 'overflow-y: auto'));

        $this->style(Tools::css($coreSelector, array(' > ', '.box', ' > ', '.closer'), 'position: absolute',
            'right: 0', 'top: 0'));

        $this->style(Tools::css($coreSelector, array(' > ', '.box', ' > ', '.pencil'), 'position: absolute',
            'right: 20px', 'top: -2px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return parent::generateOpeningTag() . '<div class="box">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</div>' . parent::generateClosingTag();
    }

    // Short cuts

    /**
     * @return DashboardHtmlView
     */
    public function parent() {
        $p = $this->getParent();
        if ($p instanceof DashboardHtmlView) {
            return $p;
        } else {
            return null;
        }
    }

    /**
     * Generate a closer view.
     * @param array|string $classes The classes.
     * @return CloserHtmlView
     */
    public function closer($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $closer = new CloserHtmlView($classes);
        $this->add($closer);
        return $closer;
    }

    /**
     * Generate a closer view.
     * @param array|string $classes The classes.
     * @return PencilHtmlView
     */
    public function pencil($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $pencil = new PencilHtmlView($classes);
        $this->add($pencil);
        return $pencil;
    }

    /**
     * Create a view from the dashboard items.
     * @param DashboardItem $item The dashboard item.
     * @param array|string $classes (optional) The classes.
     * @return DashboardItemHtmlView
     */
    public static function _new(DashboardItem $item, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return $item->createView($classes);
    }
}
?>