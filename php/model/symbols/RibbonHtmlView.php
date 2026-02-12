<?php

/**
 * View RibbonHtmlView.
 *
 * <p>Adds a ribbon to a view.</p>
 * <img src="http://localhost/coast/webship/modeldoc/ribbon.png" />
 *
 * <code>
 * ContainerHtmlView::create(['rel'])->
 *     add(RibbonHtmlView::create())->
 *         texts("Hallo Welt!");
 * </code>
 *
 * @package webship
 * @subpackge php/model/symbols
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @inspiredBy https://codepen.io/nxworld/pen/oLdoWb
 * @view
 * @examples
 *     ContainerHtmlView::create(['rel'])->add(RibbonHtmlView::create())->texts("Hallo Welt!");
 */
class RibbonHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'ribbon';
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.ribbon', [], '--dark-ribbon-color: var(--font-info)',
            '--font-color: var(--font-info)', '--background-color: var(--border-info)'));
        self::addStyle(Tools::css('.ribbon', ['.error'], '--dark-ribbon-color: var(--error-font)',
            '--font-color: var(--error-font)', '--background-color: var(--error-border)'));

        /* common */
        self::addStyle(Tools::css('.ribbon', [], 'width: 150px', 'height: 150px',
            'overflow: hidden', 'position: absolute', 'z-index: 2'));
        self::addStyle(Tools::css('.ribbon', ['::before'], 'position: absolute', 'z-index: -1',
            'content: ""', 'display: block', 'border: 5px solid var(--dark-ribbon-color)'));
        self::addStyle(Tools::css('.ribbon', ['::after'], 'position: absolute', 'z-index: -1',
            'content: ""', 'display: block', 'border: 5px solid var(--dark-ribbon-color)'));
        self::addStyle(Tools::css('.ribbon', [' ', 'span'], 'position: absolute', 'display: flex',
            'flex-direction: row', 'align-items: center', 'justify-content: center',
            'width: 225px', 'height: 48px', 'background-color: var(--background-color)',
            'box-shadow: 0 5px 10px rgba(0,0,0,.1)',
            'color: var(--font-color)', 'font: 700 18px/1 Lato, sans-serif', 'text-shadow: 0 1px 1px rgba(0,0,0,.2)',
            'text-transform: uppercase'));

        self::addStyle(Tools::css('.ribbon.tiny', [], 'width: 50px', 'height: 50px'));
        self::addStyle(Tools::css('.ribbon.tiny', [' ', 'span'], 'display: flex',
            'flex-direction: row', 'align-items: center', 'justify-content: center', 'width: 74px', 'font-size: 11px',
            'padding: 0', 'height: 15px', 'top: 13px', 'right: -6px', 'font-family: monospace',
            'box-shadow: 0 1.33px 3.33px rgba(0,0,0,.1)'));
        self::addStyle(Tools::css('.ribbon.tiny', ['::before'],
            'border: 2.5px solid var(--dark-ribbon-color)'));
        self::addStyle(Tools::css('.ribbon.tiny', ['::after'],
            'border: 2.5px solid var(--dark-ribbon-color)'));

        /* top left*/

        self::addStyle(Tools::css('.ribbon', ['.top-left'], 'top: -10px', 'left: -10px'));
        self::addStyle(Tools::css('.ribbon', ['.top-left::before'], 'border-top-color: transparent',
            'border-left-color: transparent'));
        self::addStyle(Tools::css('.ribbon', ['.top-left::after'], 'border-top-color: transparent',
            'border-left-color: transparent'));
        self::addStyle(Tools::css('.ribbon', ['.top-left::before'], 'top: 0', 'right: 0'));
        self::addStyle(Tools::css('.ribbon', ['.top-left::after'], 'bottom: 0', 'left: 0'));
        self::addStyle(Tools::css('.ribbon', ['.top-left', ' ', 'span'], 'right: -25px', 'top: 30px',
            'transform: rotate(-45deg)'));

        self::addStyle(Tools::css('.ribbon.tiny', ['.top-left'], 'top: -5px', 'left: -5px'));
        self::addStyle(Tools::css('.ribbon', ['.top-left.tiny', ' ', 'span'], 'right: -7px', 'top: 12px'));

        /* top right*/
        self::addStyle(Tools::css('.ribbon', ['.top-right'], 'top: -10px', 'right: -10px'));
        self::addStyle(Tools::css('.ribbon', ['.top-right::before'], 'border-top-color: transparent',
            'border-right-color: transparent'));
        self::addStyle(Tools::css('.ribbon', ['.top-right::after'],  'border-top-color: transparent',
            'border-right-color: transparent'));
        self::addStyle(Tools::css('.ribbon', ['.top-right::before'], 'top: 0', 'left: 0'));
        self::addStyle(Tools::css('.ribbon', ['.top-right::after'], 'bottom: 0', 'right: 0'));
        self::addStyle(Tools::css('.ribbon', ['.top-right', ' ', 'span'], 'left: -25px', 'top: 30px',
            'transform: rotate(45deg)'));

        /* bottom left*/
        self::addStyle(Tools::css('.ribbon', ['.bottom-left'], 'bottom: -10px', 'left: -10px'));
        self::addStyle(Tools::css('.ribbon', ['.bottom-left::before'], 'border-bottom-color: transparent',
            'border-left-color: transparent'));
        self::addStyle(Tools::css('.ribbon', ['.bottom-left::after'], 'border-bottom-color: transparent',
            'border-left-color: transparent'));
        self::addStyle(Tools::css('.ribbon', ['.bottom-left::before'], 'bottom: 0', 'right: 0'));
        self::addStyle(Tools::css('.ribbon', ['.bottom-left::after'], 'top: 0', 'left: 0'));
        self::addStyle(Tools::css('.ribbon', ['.bottom-left', ' ', 'span'], 'right: -25px', 'bottom: 30px',
            'transform: rotate(225deg)'));

        /* bottom right*/
        self::addStyle(Tools::css('.ribbon', ['.bottom-right'], 'bottom: -10px', 'right: -10px' ));
        self::addStyle(Tools::css('.ribbon', ['.bottom-right::before'], 'border-bottom-color: transparent',
            'border-right-color: transparent'));
        self::addStyle(Tools::css('.ribbon', ['.bottom-right::after'], 'border-bottom-color: transparent',
            'border-right-color: transparent'));
        self::addStyle(Tools::css('.ribbon', ['.bottom-right::before'], 'bottom: 0', 'left: 0'));
        self::addStyle(Tools::css('.ribbon', ['.bottom-right::after'], 'top: 0', 'right: 0'));
        self::addStyle(Tools::css('.ribbon', ['.bottom-right', ' ', 'span'], 'left: -25px', 'bottom: 30px',
            'transform: rotate(-225deg)'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return parent::generateOpeningTag() . '<span>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</span>' . parent::generateClosingTag();
    }

    /**
     * Create a ribbon.
     * @param bool $top If it shall be in the top (false = bottom).
     * @param bool $left If it shall be in the left (false = right).
     * @param string[]|string $classes Some classes.
     * @return RibbonHtmlView
     */
    public static function create(bool $top = true, bool $left = true, array|string $classes = []) : RibbonHtmlView {
        if (!is_array($classes)) $classes = [ $classes ];
        if ($top && $left) $classes[] = 'top-left';
        if ($top && !$left) $classes[] = 'top-right';
        if (!$top && $left) $classes[] = 'bottom-left';
        if (!$top && !$left) $classes[] = 'bottom-right';
        return new RibbonHtmlView($classes);
    }

    /**
     * Set it to tiny.
     * @return $this
     */
    public function tiny() : static {
        $this->addClass('tiny');
        return $this;
    }

    /**
     * Set it as an error.
     * @return $this
     */
    public function error() : static {
        $this->addClass('error');
        return $this;
    }
}
?>