<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class LinkHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class LinkHtmlView extends HtmlView {

    /**
     * The url.
     * @var string
     */
    private $url;

    /**
     * Download.
     * @var boolean
     */
    private $download;

    /**
     * Target
     * @var string
     */
    private $target;

    /**
     * LinkHtmlView constructor.
     * @param string $url The url.
     * @param array|string $classes
     * @param boolean $download Whether the result should be downloaded.
     * @param string $target The target.
     */
    public function __construct($url, $classes = array(), $download = false, $target = '_blank') {
        parent::__construct(array_merge(array('a'), is_array($classes) ? $classes : [$classes]));
        $this->url = $url;
        $this->download = $download;
        $this->target = $target;

        $this->style(Tools::css('a', '', 'text-decoration: none', 'display: flex',
            'justify-content: center', 'align-items: center', 'text-align: center', 'color: rgba(0, 0, 0, 1.0)',
            'box-sizing: border-box'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<a href="' . $this->url . '"' . ($this->download ? ' download' : '') . ' class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '" target="' . $this->target . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</a>';
    }

    // Short cuts

}
?>