<?php

namespace webship\panels;

use webship\containers\ContainerHtmlView;

/**
 * View PanelItemHtmlView.
 *
 * <p>A subview of a {@link PanelHtmlView} that is just a {@link ContainerHtmlView} with a special CSS class
 * <em>panel-item</em>.</p>
 * @see ContainerHtmlView
 * <code>
 * $panel = PanelHtmlView::createIt();
 * $panel->item()->padding(10)->texts("Hello World!");
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/panel.png" />
 *
 * @title PanelItem
 * @category panels
 * 
 * @package webship
 * @subpackage php/model/panels
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     $panel = PanelHtmlView::createIt();
 *     $panel->item()->padding(10)->texts("Hello World!");
 */
class PanelItemHtmlView extends ContainerHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'panel-item';
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void { }

    //
    // Shortcuts
    //

    /**
     * @return ?PanelHtmlView
     */
    public function parent() : ?PanelHtmlView {
        $p = $this->getParent();
        if ($p instanceof PanelHtmlView) {
            return $p;
        } else {
            return null;
        }
    }
}
?>