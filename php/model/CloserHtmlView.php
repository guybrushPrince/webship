<?php

/**
 * Class CloserHtmlView.
 *
 * @package webship
 * @subpackage php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 */
class CloserHtmlView extends StandardButtonHtmlView {

    /**
     * CloserHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(string|array $classes = [], string $img = '/tardis/pic/cancel.svg') {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct('closer', array_merge([ 'closer' ], $classes));

        $this->img($img);
        $this->onlyImg(true);

        $this->style(Tools::css('button.std-button.closer', [],
            'box-sizing: border-box'));

        $this->style(Tools::css('button.std-button.closer.mini', [], 'min-height: 25px',
            'min-width: 25px', 'height: 25px', 'width: 25px', 'border: none', 'margin: 0'));

        $this->style(Tools::css('button.std-button.closer.plain', [' > ','img'], 'border-radius: 50%',
            'transition: background 0.25s'));
        $this->style(Tools::css('button.std-button.closer.plain:hover', [' > ','img'],
            'background: var(--dark-system-background)'));
    }

    /**
     * Return a new closer html view.
     * @param string[]|string $classes Some classes.
     * @param string $img A path to an image.
     * @return CloserHtmlView
     */
    public static function _new(string|array $classes = [], string $img = '/tardis/pic/cancel.svg') : CloserHtmlView {
        return new CloserHtmlView($classes, $img);
    }

    /**
     * Sets it mini.
     * @return $this
     */
    public function mini() : static {
        $this->addClass('mini');
        return $this;
    }
}
?>