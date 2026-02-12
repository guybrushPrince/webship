<?php

namespace webship\inputs;

use HtmlView;
use ship\views\TextView;
use Tools;

/**
 * View DropDownHtmlView.
 *
 * <p>A drop-down menu, which allows for subviews but opens to down.</p>
 *
 * <code>
 * $dropDown = DropDownHtmlView::create();
 * $dropDown->texts('Drop me');
 * $dropDown->entry()->texts('Entry 1');
 * $dropDown->entry()->texts('Entry 2');
 * $dropDown->entry()->texts('Entry 3');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/dropdown.png" />
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @version 1.1.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 *
 * @view
 * @examples
 *     $dropDown = DropDownHtmlView::create();
 *     $dropDown->texts('Drop me');
 *     $dropDown->entry()->texts('Entry 1');
 *     $dropDown->entry()->texts('Entry 2');
 *     $dropDown->entry()->texts('Entry 3');
 */
class DropDownHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['drop-down'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.drop-down', [], 'position: relative', 'display: inline-flex',
            'flex-direction: column', 'width: fit-content', 'transition: width 0.5s'));
        self::addStyle(Tools::css('.drop-down:hover', [],
            'width: 100px'));
        self::addStyle(Tools::css('.drop-down:hover', [' > ', '[data-drop-down-content]'],
            'position: absolute', 'z-index: 10000', 'top: 0'));
        self::addStyle(Tools::css('.drop-down.fix:hover', [' > ', '[data-drop-down-content]'],
            'position: fixed'));
        self::addStyle(Tools::css('.drop-down', [' > ', '[data-drop-down-content]'],
            'margin: 0', 'padding: 0'));
        self::addStyle(Tools::css('.drop-down', [' > ', '[data-drop-down-content]', ' > ', ':not(:first-child)'],
            'display: none'));
        self::addStyle(Tools::css('.drop-down', [' > ', '[data-drop-down-content]', ' > ', '*:first-child'],
            'font-weight: bold'));
        self::addStyle(Tools::css('.drop-down:hover', [' > ', '[data-drop-down-content]', ' > ',
            ':not(:first-child)'], 'display: block'));
        self::addStyle(Tools::css('.drop-down', [' > ', '[data-drop-down-content]', ' > ', '*'],
            'width: fit-content', 'height: 35px', 'padding: 10px 20px', 'box-sizing: border-box',
            'position: relative', 'width: -moz-fit-content', 'background: var(--main-menu-hover)',
            'white-space: nowrap'));
    }

    /**
     * Create a drop-down menu.
     * @param string[]|string $classes Some classes.
     * @return DropDownHtmlView
     */
    public static function create(array|string $classes = []) : DropDownHtmlView {
        return new DropDownHtmlView($classes);
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        if (!Tools::isEmpty($this->subviews) && $this->subviews[0] instanceof TextView) {
            $header = array_shift($this->subviews);
            $entry = DropDownEntryHtmlView::create();
            $entry->add($header);
            array_unshift($this->subviews, $entry);
        }
        return '<div class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">' .
            '<div data-drop-down-content>';

    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</div></div>';
    }

    /**
     * Create an entry.
     * @param string[]|string $classes A set of classes.
     * @return DropDownEntryHtmlView
     */
    public function entry(array|string $classes = []) : DropDownEntryHtmlView {
        $entry = DropDownEntryHtmlView::create($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Set this view static to the side.
     * @return $this
     */
    public function fix() : static {
        $this->addClass('fix');
        return $this;
    }
}
?>