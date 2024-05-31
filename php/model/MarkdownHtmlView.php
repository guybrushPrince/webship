<?php

/**
 * Class MarkdownHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class MarkdownHtmlView extends HtmlView {

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
     * MarkdownHtmlView constructor.
     * @param string $fileOrContent The file or the markdown content.
     * @param bool $isFile Is it a path to a file or the content.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(string $fileOrContent, bool $isFile = true, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(['markdown'], $classes));
        $this->fileOrContent = $fileOrContent;
        $this->isFile = $isFile;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'width: 100%'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        $parsedown = new ParsedownExtra();
        return parent::generateOpeningTag() .
            ($this->isFile ? $parsedown->text(file_get_contents($this->fileOrContent)) :
                             $parsedown->text($this->fileOrContent));
    }

    /**
     * Create a markdown view..
     * @param string $content The content.
     * @param string[]|string $classes Classes.
     * @return MarkdownHtmlView
     */
    public static function create(string $content, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new MarkdownHtmlView($content, false, $classes);
    }
}
?>