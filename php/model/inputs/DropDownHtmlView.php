<?php

/**
 * Class DropDownHtmlView.
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DropDownHtmlView extends HtmlView {

    /**
     * DropDownHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['drop-down'], $classes));

        $this->style(Tools::css('.drop-down', [], 'position: relative', 'display: inline-flex',
            'flex-direction: column', 'width: fit-content', 'transition: width 0.5s'));
        $this->style(Tools::css('.drop-down:hover', [],
            'width: 100px'));
        $this->style(Tools::css('.drop-down:hover', [' > ', '[data-drop-down-content]'],
            'position: fixed', 'z-index: 10000', 'top: 0'));
        $this->style(Tools::css('.drop-down', [' > ', '[data-drop-down-content]'],
            'margin: 0', 'padding: 0'));
        $this->style(Tools::css('.drop-down', [' > ', '[data-drop-down-content]', ' > ', ':not(:first-child)'],
            'display: none'));
        $this->style(Tools::css('.drop-down', [' > ', '[data-drop-down-content]', ' > ', '*:first-child'],
            'font-weight: bold'));
        $this->style(Tools::css('.drop-down:hover', [' > ', '[data-drop-down-content]', ' > ',
            ':not(:first-child)'], 'display: block'));
        $this->style(Tools::css('.drop-down', [' > ', '[data-drop-down-content]', ' > ', '*'],
            'width: fit-content', 'height: 35px', 'padding: 10px 20px', 'box-sizing: border-box',
            'position: relative', 'width: -moz-fit-content', 'background: var(--main-menu-hover)'));
    }

    /**
     * Create a drop-down menu.
     * @param string|array $classes
     * @return DropDownHtmlView
     */
    public static function create(string|array $classes = []) : DropDownHtmlView {
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
    public function entry(string|array $classes = []) : DropDownEntryHtmlView {
        $entry = DropDownEntryHtmlView::create($classes);
        $this->add($entry);
        return $entry;
    }

    /**
     * Create a sub drop-down entry.
     * @param string[]|string $classes A set of classes.
     * @return SubDropDownHtmlView
     */
    /*public function sub(string|array $classes = []) : SubDropDownHtmlView {
        $entry = SubDropDownHtmlView::create($classes);
        $this->add($entry);
        return $entry;
    }*/
}
?>