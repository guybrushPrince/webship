<?php

/**
 * View DropDownEntryHtmlView.
 *
 * <p> An entry for a DropDownHtmlView. </p>
 *
 * <code>
 * $view = DropDownEntryHtmlView::create();
 * </code>
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @view

 */
class DropDownEntryHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['drop-down-entry'], $classes));

        self::addCSS();
    }

    /**
     * Create a drop-down entry.
     * @param string|string[] $classes Some classes.
     * @return DropDownEntryHtmlView
     */
    public static function create(string|array $classes = []) : DropDownEntryHtmlView {
        return new DropDownEntryHtmlView($classes);
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.drop-down-entry', [], 'display: flex', 'flex-wrap: wrap',
            'cursor: pointer', 'font-size: 15px', 'font-weight: normal', 'align-items: center'));
        self::addStyle(Tools::css('.drop-down-entry', [':hover'], 'background: var(--main-menu)'));
        self::addStyle(Tools::css('.drop-down-entry', [':hover', ' > ', ' ul.sub-menu-entry'],
            'display: flex', 'flex-wrap: wrap', 'position: absolute', 'left: 0', 'top: 35px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<div data-drop-down-entry' .
            ($this->notices ? ' title="' . (array_key_exists(View::getLanguage(), $this->notices) ? $this->notices[View::getLanguage()] : array_pop($this->notices)) . '"' : '') .
            ' class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</div>';
    }
}
?>