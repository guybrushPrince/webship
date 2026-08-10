<?php

namespace webship\links;

use webship\core\HtmlView;

/**
 * View AnchorHtmlView.
 *
 * <p>A textual anchor to which it can be referred within a {@link LinkHtmlView}. This view is usually only visible
 * through its children.</p>
 * <code>AnchorHtmlView::create('link-to-me');</code>
 *
 * @title Anchor
 * @category links
 * 
 * @package webship
 * @subpackage php/model/anchors
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     AnchorHtmlView::create('link-to-me');
 */
class AnchorHtmlView extends HtmlView {

    /**
     * The name of the anchor.
     * @var string
     */
    private string $name;

    /**
     * Constructor.
     * @param string $name The name.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string $name, array|string $classes = []) {
        parent::__construct(array_merge(['anchor'], is_array($classes) ? $classes : [$classes]));
        $this->name = $name;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
    }

    /**
     * Create an anchor.
     * @param string $name The name.
     * @param string[]|string $classes Some classes.
     * @return AnchorHtmlView
     */
    public static function create(string $name, array|string $classes = []) : AnchorHtmlView {
        return new AnchorHtmlView($name, $classes);
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $opening = parent::generateOpeningTag();
        return '<a name="' . $this->name . '"' . substr($opening, 4);
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</a>';
    }

    /**
     * Get the name (anchor).
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }
}
?>