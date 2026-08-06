<?php

namespace webship\containers;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * Class ChipContainerHtmlView
 *
 * @name ChipContainerHtmlView
 * @category containers
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
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['chip-container'], $classes));

        $this->style(WebshipTools::css('.chip-container', '', 'align-items: center', 'gap: 5px'));

        $this->style(WebshipTools::css('.chip-container', [' > ', '.chip'], 'align-items: center',
            'padding: 5px 5px 5px 10px', 'height: 45px', 'box-sizing: border-box', 'gap: 10px', 'width: auto',
            'flex-wrap: nowrap'));

        $this->style(WebshipTools::css('.chip-container', [' > ', '.chip:not(.adder)', ' > ',
            ':last-child:not(.closer)'], 'padding-right: 5px'));
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