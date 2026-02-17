<?php

namespace webship\panels;

use webship\titles\TitleHtmlView;

/**
 * View PanelHeaderHtmlView.
 *
 * <p>A panel may have a header this is a specialization of a {@link TitleHtmlView}.</p>
 * @see TitleHtmlView
 * <code>
 * $panel = PanelHtmlView::createIt();
 * $panel->header([], "Hello World");
 * </code>
 *
 * @package webship
 * @subpackge php/model/panels
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.3.0
 *
 * @view
 * @examples
 */
class PanelHeaderHtmlView extends TitleHtmlView {

    /**
     * Constructor.
     * @param string|null $dataType The type of the header if desired.
     * @param string|null $img The name of an image.
     * @param string|string[] $classes Some classes.
     */
    private function __construct(?string $dataType = null, ?string $img = null,
                                 array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'panel-header';
        parent::__construct($dataType, $img, $classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
    }

    /**
     * @inheritDoc
     * @return PanelHeaderHtmlView
     */
    public static function create(string|array|null $title, ?string $dataType = null, ?string $img = null,
                                  string|array $classes = []) : PanelHeaderHtmlView {
        $view = new PanelHeaderHtmlView($dataType, $img, $classes);
        if (!is_null($title)) {
            if (!is_array($title)) $title = [self::getLanguage() => $title];
            $view->texts($title);
        }
        return $view;
    }

    //
    // Shortcuts
    //

    /**
     * Get the parent.
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