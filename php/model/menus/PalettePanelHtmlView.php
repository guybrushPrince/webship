<?php

namespace webship\menus;

use webship\buttons\StandardButtonHtmlView;
use webship\containers\ContainerHtmlView;
use webship\WebshipTools;

/**
 * View PalettePanelHtmlView.
 *
 * <p>A typical editor palette of different (relatively big) buttons with an effect to the edited model.</p>
 *
 * <code>
 * $palette = PalettePanelHtmlView::create();
 * $palette->button('button1', '/webship/pic/add.svg');
 * $palette->button('button2', '/webship/pic/delete.svg');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/palettepanel.png" />
 *
 * @package webship
 * @subpackage php/model/menus
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $palette = PalettePanelHtmlView::create();
 *     $palette->button('button1', '/webship/pic/add.svg');
 *     $palette->button('button2', '/webship/pic/delete.svg');
 */
class PalettePanelHtmlView extends ContainerHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'palette';
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.palette.container', [], 'background: var(--ws-std-bg-light)',
            'border: 1px solid var(--ws-std-brd)', 'border-radius: 3px', 'box-sizing: content-box', 'opacity: 0.4',
            'transition: 0.25s opacity',
            '--ws-pal-size: 45px'));
        self::addStyle(WebshipTools::css('.palette.container', ['.detached'], 'position: fixed',
            'right: 25px', 'z-index: 100'));
        self::addStyle(WebshipTools::css('.palette.container', [':hover'], 'opacity: 1.0'));
        self::addStyle(WebshipTools::css('.palette.container.ver', [], 'width: var(--ws-pal-size)'));
        self::addStyle(WebshipTools::css('.palette.container.hor', [], 'height: var(--ws-pal-size)'));
        self::addStyle(WebshipTools::css('.palette', [' > ', 'button.std-button.only-img'],
            'width: var(--ws-pal-size)', 'height: var(--ws-pal-size)', 'min-width: var(--ws-pal-size)',
            'min-height: var(--ws-pal-size)', 'background: none', 'border: none', 'padding: 10px', 'box-shadow: none',
            'transition: 0.25s background', 'border-radius: 0'));
        self::addStyle(WebshipTools::css('.palette', [' > ', 'button.std-button.only-img', ':hover'],
            'background: var(--ws-std-bg)'));
    }

    /**
     * Create a palette.
     * @param bool $vertical If the palette shall be horizontally oriented or vertical.
     * @param bool $detached If the palette shall be detached from the surrounding view, or not.
     * @param string[]|string $classes Some classes.
     * @return PalettePanelHtmlView
     */
    public static function create(bool $vertical = true, bool $detached = false,
                                  array|string $classes = []) : PalettePanelHtmlView {
        $view = new PalettePanelHtmlView($classes);
        if ($vertical) $view->vertical();
        else $view->horizontal();
        if ($detached) $view->addClass('detached');

        return $view;
    }

    /**
     * Adds a new button.
     * @param string $id The id of the button.
     * @param string $img The image of the button.
     * @param string[]|string $classes Some classes.
     * @return StandardButtonHtmlView
     */
    public function button(string $id, string $img, array|string $classes = []) : StandardButtonHtmlView {
        $button = StandardButtonHtmlView::create($id, $classes);
        $button->onlyImg($img);
        $this->add($button);
        return $button;
    }
}
?>