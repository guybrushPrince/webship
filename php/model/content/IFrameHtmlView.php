<?php

namespace webship\contents;

use Exception;
use ship\views\View;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View IFrameHtmlView.
 *
 * <p>Presents an HTML iframe element with external URL for embedded HTML content.</p>
 *
 * <code>
 * $iframe = IFrameHtmlView::create(null, 'https://example.com');
 * </code>
 * 
 * <img src="http://localhost/coast/webship/modeldoc/iframe.png" />
 *
 * @title IFrame
 * @category content
 * 
 * @package webship
 * @subpackage php/model/content
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $iframe = IFrameHtmlView::create(null, 'https://example.com');
 */
class IFrameHtmlView extends HtmlView {

    /**
     * The content.
     * @var string|bool|View|null
     */
    private string|bool|View|null $content;

    /**
     * The url.
     * @var string|bool|null
     */
    private string|bool|null $url;

    /**
     * Constructor.
     * @param View|string|null|false $content The view to present.
     * @param string|null|false $url The url.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(View|string|bool|null $content = null, string|bool|null $url = null,
                                array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['iframe'], $classes));
        $this->content = $content;
        $this->url = $url;
    
        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('iframe', [], 'border: none', 'box-sizing: border-box',
            'width: 100%', 'height: 100%'));
    }

    /**
     * Creates a new IFrame view.
     * @param View|string|null|false $content The view to present.
     * @param string|null|false $url The url.
     * @param string[]|string $classes Some classes.
     * @return IFrameHtmlView
     */
    public static function create(View|string|bool|null $content = null, string|bool|null $url = null,
                                  array|string $classes = []) : IFrameHtmlView {
        return new IFrameHtmlView($content, $url, $classes);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function generateOpeningTag() : string {
        return '<iframe class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->url ? ' src="' . $this->url . '"' : '') .
            ($this->content ? ' srcdoc="' .
                str_replace("\"", "'",
                    ($this->content instanceof View ? $this->content->generateSafe() : $this->content)) . '"' : '') .
            '>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</iframe>';
    }
}
?>