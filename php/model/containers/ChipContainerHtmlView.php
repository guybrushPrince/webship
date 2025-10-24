<?php

/**
 * Class ChipContainerHtmlView
 *
 * @package webship
 * @subpackage php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 */
class ChipContainerHtmlView extends ContainerHtmlView {

    /**
     * ChipContainerHtmlView constructor.
     * @param string[]|string $classes An array of additional classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['chip-container'], $classes));

        $this->style(Tools::css('.chip-container', '', 'align-items: center', 'gap: 5px'));

        $this->style(Tools::css('.chip-container', [' > ', '.chip'],'align-items: center',
            'padding: 5px 5px 5px 10px', 'height: 45px', 'box-sizing: border-box', 'gap: 10px', 'width: auto',
            'flex-wrap: nowrap'));
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