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
     */
    public function __construct(string $content) {
        parent::__construct([]);
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