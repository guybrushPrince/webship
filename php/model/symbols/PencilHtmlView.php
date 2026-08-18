<?php

namespace webship\symbols;

use webship\buttons\StandardButtonHtmlView;
use webship\menus\ControlPanelHtmlView;
use webship\WebshipTools;

/**
 * View PencilHtmlView.
 *
 * <p>Presents a {@link StandardButtonHtmlView} with a pencil icon, especially for {@link ControlPanelHtmlView}.</p>
 *
 * <img src="http://localhost/coast/webship/modeldoc/pencil.png" />
 *
 * <code>PencilHtmlView::_new();</code>
 *
 * @title Pencil
 * @category symbols
 * 
 * @package webship
 * @subpackage php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Franziska Lüder
 * @version 1.2.0
 *
 * @view
 * @examples
 *      PencilHtmlView::_new();
 */
class PencilHtmlView extends StandardButtonHtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes.
     */
    private function __construct(string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct('pencil', array_merge(['pencil'], $classes));
        self::addCSS();
        $this->onlyImg(true);
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('button.std-button.pencil', [], 'color: var(--ws-font-info)'));
        self::addStyle(WebshipTools::css('button.std-button.pencil', [':after'], 'content: "\270E"',
            'width: 100%', 'text-align: center', 'font-size: 14px', 'line-height: 15px'));

        self::addStyle(WebshipTools::css('button.std-button.pencil', ':hover',
            'color: var(--ws-neutral)'));
    }

    /**
     * Return a new pencil html view.
     * @param string[]|string $classes Classes.
     * @return PencilHtmlView
     */
    public static function _new(string|array $classes = []) : PencilHtmlView {
        return new PencilHtmlView($classes);
    }
}
?>