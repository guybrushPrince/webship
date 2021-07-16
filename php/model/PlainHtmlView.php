<?php
/**
 * Class PlainHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
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