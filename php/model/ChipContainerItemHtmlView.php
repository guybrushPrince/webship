<?php

include_once dirname(__FILE__) . '/ContainerItemHtmlView.php';

/**
 * Class ChipContainerItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ChipContainerItemHtmlView extends ContainerItemHtmlView {

    /**
     * ContainerItemHtmlView constructor.
     * @param array $classes
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('chip'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, array(' > ', '.closer'), 'width: 10px', 'margin-left: 10px'));
        $this->style(Tools::css($coreSelector, array(' ', '.container'), 'height: auto', 'padding-top: 0',
            'padding-bottom: 0'));
        $this->style(Tools::css($coreSelector, array(' ', '.container-item'), 'height: auto', 'padding-top: 0',
            'padding-bottom: 0'));
        $this->style(Tools::css($coreSelector, array(' ', 'select'), 'height: auto'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        if ($this->subviews[0] instanceof CloserHtmlView) {
            array_push($this->subviews, array_shift($this->subviews));
        }
        return parent::generateOpeningTag();
    }

    // Short cuts

    /**
     * Generate a closer view.
     * @param array $classes The classes.
     * @return CloserHtmlView
     */
    public function closer(array $classes = array()) {
        $closer = new CloserHtmlView($classes);
        $this->add($closer);
        return $closer;
    }

}
?>