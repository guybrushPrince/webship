<?php

include_once dirname(__FILE__) . '/ContainerItemHtmlView.php';

/**
 * Class ChipContainerItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ChipContainerItemHtmlView extends ContainerItemHtmlView {

    /**
     * ContainerItemHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
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
     * @param array|string $classes The classes.
     * @return CloserHtmlView
     */
    public function closer($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $closer = new CloserHtmlView($classes);
        $this->add($closer);
        return $closer;
    }

}
?>