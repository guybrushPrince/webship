<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class MarkdownHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class MarkdownHtmlView extends HtmlView {

    /**
     * The file.
     * @var string
     */
    private $file;

    /**
     * MarkdownHtmlView constructor.
     * @param string $file The file.
     * @param array $classes Some classes.
     */
    public function __construct($file, array $classes = array()) {
        parent::__construct(array_merge(array('markdown'), $classes));
        $this->file = $file;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'width: 100%'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $parsedown = new ParsedownExtra();
        return parent::generateOpeningTag() . $parsedown->text(file_get_contents($this->file));
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return parent::generateClosingTag();
    }



    // Short cuts

    /**
     * Return a new container html view.
     * @param string $file The file.
     * @param array $classes Classes.
     * @return MarkdownHtmlView
     */
    public static function _new($file, array $classes) {
        return new MarkdownHtmlView($file, $classes);
    }
}
?>