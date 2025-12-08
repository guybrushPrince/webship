<?php

/**
 * View MarkdownHtmlView
 *
 * <p>Displays the contents of a markdown file or directly displays markdown content.</p>
 * <code>
 * MarkdownHtmlView::create('# Markdown Heading' . PHP_EOL . PHP_EOL . 'This is text in a markdown file');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/markdown.png" />
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Franziska Lüder
 * @version 1.2.0
 * @view
 *
 * @examples
 *      MarkdownHtmlView::create('# Markdown Heading' . PHP_EOL . PHP_EOL . 'This is text in a markdown file');
 */
class MarkdownHtmlView extends ContainerHtmlView {

    /**
     * The file or the markdown content.
     * @var string
     */
    private string $fileOrContent;

    /**
     * Whether the file or content contains a path to a file, or not.
     * @var bool
     */
    private bool $isFile;

    /**
     * Whether the origin (the base path) of pictures shall be changed.
     * @var string|null
     */
    private ?string $replaceOrigin;

    /**
     * MarkdownHtmlView.
     * @param string $fileOrContent The file or the markdown content.
     * @param bool $isFile Is it a path to a file or the content.
     * @param string[]|string $classes Some classes.
     * @param string|null $replaceOrigin Whether the origin (the base path) of pictures shall be changed.
     */
    private function __construct(string $fileOrContent, bool $isFile = true, array|string $classes = [],
                                ?string $replaceOrigin = null) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'markdown' ], $classes));

        $this->fileOrContent = $fileOrContent;
        $this->isFile = $isFile;
        $this->replaceOrigin = $replaceOrigin;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.markdown', [], 'width: 100%', 'display: block'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $parsedown = new ParsedownExtra();
        $text = ($this->isFile ? $parsedown->text(file_get_contents($this->fileOrContent)) :
                $parsedown->text($this->fileOrContent));
        if ($this->replaceOrigin) {
            $text = str_replace('img src="', 'img src="' . $this->replaceOrigin, $text);
        }
        return parent::generateOpeningTag() . $text;
    }

    /**
     * Create a markdown view..
     * @param string $fileOrContent The file or the markdown content.
     * @param bool $isFile Whether the first argument represents a file or the content to display.
     * @param string[]|string $classes Classes.
     * @param string|null $replaceOrigin Whether the origin (the base path) of pictures shall be changed.
     * @return MarkdownHtmlView
     */
    public static function create(string $fileOrContent, bool $isFile, array|string $classes = [],
                                  ?string $replaceOrigin = null) : MarkdownHtmlView {
        $classes = is_array($classes) ? $classes : [ $classes ];
        return new MarkdownHtmlView($fileOrContent, $isFile, $classes, $replaceOrigin);
    }
}
?>