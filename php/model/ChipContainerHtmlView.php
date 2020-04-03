<?php

include_once dirname(__FILE__) . '/ContainerHtmlView.php';

/**
 * Class ChipContainerHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ChipContainerHtmlView extends ContainerHtmlView {

    /**
     * ChipContainerHtmlView constructor.
     * @param array $classes An array of additional classes.
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('chip-container'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'align-items: center'));

        $this->style(Tools::css($coreSelector, array(' > ', '.chip'), 'background: rgba(255, 147, 46, 1)',
            'align-items: center', 'font-weight: 500', 'padding: 5px 15px', 'border-radius: 45px', 'height: 45px',
            'margin: 0 5px 5px 0', 'box-sizing: border-box'));
    }

    /**
     * Create a new chip.
     * @param array $classes An array of additional classes.
     * @return ContainerItemHtmlView
     */
    public function chip(array $classes = array()) {
        return $this->add(new ChipContainerItemHtmlView($classes));
    }

    /**
     * @inheritdoc
     */
    public function item(array $classes = array()) {
        return $this->chip($classes);
    }
}
?>