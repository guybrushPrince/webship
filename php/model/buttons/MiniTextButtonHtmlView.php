<?php

namespace webship\buttons;

use webship\WebshipTools;

/**
 * View MiniTextButtonHtmlView.
 *
 * <p>A mini textual button that gets a color inversion when hovering.</p>
 * <img src="http://localhost/coast/webship/modeldoc/minitextbutton.png" />
 *
 * <code>
 * MiniTextButtonHtmlView::create('example-1', 'Press me!');
 * MiniTextButtonHtmlView::delete('example-2', 'Delete me!');
 * </code>
 *
 * @name MiniTextButtonHtmlView
 * @category buttons
 * @package webship
 * @subpackge php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     MiniTextButtonHtmlView::create('example-1', 'Press me!');
 *     MiniTextButtonHtmlView::delete('example-2', 'Delete me!');
 */
class MiniTextButtonHtmlView extends ButtonHtmlView {

    /**
     * Constructor.
     * @param string|null $id The id.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string|null $id, array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'mini';
        parent::__construct($id, 'button', $classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('button.mini', [],'border: none', 'background: none',
            'height: 20px', 'border-radius: 3px', 'padding: 3px', 'width: initial', 'font-size: 10px',
            'color: var(--ws-font-info)', 'transition: all 0.25s', 'cursor: pointer'));
        self::addStyle(WebshipTools::css('button.mini.wb', [], 'border: 1px solid var(--ws-font-info)',
            'box-sizing: border-box'));
        self::addStyle(WebshipTools::css('button.mini', [':hover'], 'background: var(--ws-font-info)',
            'color: var(--ws-neutral)'));
        self::addStyle(WebshipTools::css('button.mini.del', [':hover'],'background: var(--ws-del-tr)',
            'color: var(--ws-neutral)'));
    }

    /**
     * Creates a new mini text button.
     * @param string|null $id The id.
     * @param string|string[] $texts The button texts.
     * @param string[]|string $classes Some classes.
     * @return MiniTextButtonHtmlView
     */
    public static function create(string|null $id, string|array $texts = [],
                                  array|string $classes = []) : MiniTextButtonHtmlView {
        $view = new MiniTextButtonHtmlView($id, $classes);
        $view->texts($texts);
        return $view;
    }

    /**
     * Creates a delete text button.
     * @param string|null $id The id.
     * @param string|string[] $texts The button texts.
     * @param string[]|string $classes Some classes.
     * @return MiniTextButtonHtmlView
     */
    public static function delete(string|null $id, string|array $texts = [],
                                  array|string $classes = []) : MiniTextButtonHtmlView {
        $view = self::create($id, $texts, $classes);
        $view->addClass('del');
        return $view;
    }

    /**
     * Set that the button has a border.
     * @return $this
     */
    public function withBorder() : static {
        $this->addClass('wb');
        return $this;
    }
}
?>