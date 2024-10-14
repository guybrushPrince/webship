<?php

include_once dirname(__FILE__) . '/ContainerHtmlView.php';

/**
 * Class ChipContainerHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ChipContainerHtmlView extends ContainerHtmlView {

    /**
     * ChipContainerHtmlView constructor.
     * @param array|string $classes An array of additional classes.
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('chip-container'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'align-items: center'));

        $this->style(Tools::css($coreSelector, array(' > ', '.chip'), 'background: var(--chip-background)',
            'align-items: center', 'font-weight: 500', 'padding: 5px 15px', 'border-radius: 45px', 'height: 45px',
            'margin: 0 5px 5px 0', 'box-sizing: border-box'));
    }

    /**
     * Create a new chip.
     * @param string[]|string $classes An array of additional classes.
     * @return ChipContainerItemHtmlView
     */
    public function chip(string|array $classes = array()) : ChipContainerItemHtmlView {
        $chip = new ChipContainerItemHtmlView($classes);
        $this->add($chip);
        return $chip;
    }

    /**
     * @inheritdoc
     */
    public function item($classes = array()) : HtmlView {
        return $this->chip($classes);
    }

    /**
     * @inheritDoc
     */
    public static function _new(array|string $classes = []) : static {
        return new ChipContainerHtmlView($classes);
    }


}
?>