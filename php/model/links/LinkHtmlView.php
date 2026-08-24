<?php

namespace webship\links;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View LinkHtmlView.
 *
 * <p>A classic link to another web page. The visualization depends on the inner children views.</p>
 * <code>
 * LinkHtmlView::create("https://www.uni-jena.de")->texts("www.uni-jena.de");
 *
 * $anchor = AnchorHtmlView::create("test-anchor");
 * LinkHtmlView::create($anchor, LinkHtmlView::TARGET_SAME_PAGE)->texts("Jump to it");
 *
 * $button = StandardButtonHtmlView::create("test-button");
 * $button->texts("Press me");
 * LinkHtmlView::create($anchor, LinkHtmlView::TARGET_SAME_PAGE)->add($button);
 * </code>
 * 
 * <img src="http://localhost/coast/webship/modeldoc/link.png" />
 *
 * @title Link
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
 *     LinkHtmlView::create("https://www.uni-jena.de")->texts("www.uni-jena.de");
 *
 *     $anchor = AnchorHtmlView::create("test-anchor");
 *     LinkHtmlView::create($anchor, LinkHtmlView::TARGET_SAME_PAGE)->texts("Jump to it");
 *
 *     $button = StandardButtonHtmlView::create("test-button");
 *     $button->texts("Press me");
 *     LinkHtmlView::create($anchor, LinkHtmlView::TARGET_SAME_PAGE)->add($button);
 */
class LinkHtmlView extends HtmlView {

    public const TARGET_NEW_PAGE = '_blank';
    public const TARGET_SAME_PAGE = '_self';

    /**
     * The url to which we link.
     * @var AnchorHtmlView|string
     */
    private AnchorHtmlView|string $url;

    /**
     * If the result shall be downloaded or not.
     * @var bool
     */
    private bool $download;

    /**
     * The target page (e.g., '_blank').
     * @var string
     */
    private string $target;

    /**
     * Constructor.
     * @param AnchorHtmlView|string $url The url or reference to an {@link AnchorHtmlView}.
     * @param string[]|string $classes Some classes.
     * @param bool $download Whether the result should be downloaded, or not.
     * @param string $target The target.
     */
    private function __construct(AnchorHtmlView|string $url, array|string $classes = [],
                                 bool                  $download = false, string $target = self::TARGET_NEW_PAGE) {
        parent::__construct(array_merge(['a'], is_array($classes) ? $classes : [$classes]));
        $this->url = $url;
        $this->download = $download;
        $this->target = $target;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('a', [], 'text-decoration: none', 'display: flex',
            'justify-content: center', 'align-items: center', 'text-align: center', 'color: var(--ws-main-font)',
            'box-sizing: border-box'));
    }

    /**
     * Create a {@link LinkHtmlView}.
     * @param AnchorHtmlView|string $url The URL or reference to an {@link AnchorHtmlView}.
     * @param string $target The target location of the opened link.
     * @param string[]|string $classes Some classes.
     * @param bool $download Whether the result should be downloaded, or not.
     * @return LinkHtmlView
     */
    public static function create(AnchorHtmlView|string $url, string $target = self::TARGET_NEW_PAGE,
                                  array|string $classes = [], bool $download = false) : LinkHtmlView {
        return new LinkHtmlView($url, $classes, $download, $target);
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $opening = parent::generateOpeningTag();
        if ($this->url instanceof AnchorHtmlView) $url = '#' . $this->url->getName();
        else $url = $this->url;

        return '<a href="' . $url . '"' . ($this->download ? ' download' : '') .
            ' target="' . $this->target . '"' . substr($opening, 4);
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</a>';
    }
}
?>