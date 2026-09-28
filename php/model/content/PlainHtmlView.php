<?php

namespace webship\contents;

use webship\core\HtmlView;

/**
 * View PlainHtmlView.
 *
 * <p>An {@link HtmlView} that interprets the given text as plain HTML code.</p>
 *
 * <code>
 * PlainHtmlView::create('Hello world!');
 * PlainHtmlView::create('<table><tr><td>Column 1</td><td>Column 2</td></tr><tr><td>A</td><td>B</td></tr></table>');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/plain.png" />
 *
 * @title Plain
 * @category content
 * 
 * @package webship
 * @subpackage php/model
 *
 * @version 1.1.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Mihailo Jovic
 *
 * @view
 * @examples
 *     PlainHtmlView::create('Hello world!');
 *     PlainHtmlView::create('<table><tr><td>Column 1</td><td>Column 2</td></tr><tr><td>A</td><td>B</td></tr></table>');
 */
class PlainHtmlView extends HtmlView {

    /**
     * The plain html content.
     * @var string
     */
    private string $content;

    /**
     * Constructor.
     * @param string $content The plain html content.
     */
    private function __construct(string $content) {
        parent::__construct();
        $this->content = $content;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return $this->content;
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '';
    }

    /**
     * Create a plain html view.
     * @param string $content The HTML content.
     * @return PlainHtmlView
     */
    public static function create(string $content) : PlainHtmlView {
        return new PlainHtmlView($content);
    }
}
?>