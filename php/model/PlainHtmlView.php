<?php
/**
 * Class PlainHtmlView.
 * Presents a Plain view.
 *
 * @package webship
 * @subpackage php/model
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 *
 * @view
 * @examples
 *  Usage: PlainHtmlView::create($classes)
 *
 * @param string[]|string $classes Possible classes.
 *
 */
class PlainHtmlView extends HtmlView {

    /**
     * The plain html content.
     * @var string
     */
    private string $content;

    /**
     * PlainHtmlView constructor.
     * @param string $content The plain html content.
     * @param array|string $classes Additional classes.
     */
    public function __construct(string $content, $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(['plain'], $classes));
        $this->content = $content;
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return $this->content;
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '';
    }


}
?>