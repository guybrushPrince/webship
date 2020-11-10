<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class IFrameHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class IFrameHtmlView extends HtmlView {

    /**
     * The content.
     * @var string|boolean|View
     */
    private $content;

    /**
     * The url.
     * @var string
     */
    private $url;

    /**
     * InputHtmlView constructor.
     * @param string|boolean|View $content The id.
     * @param string|boolean $url The url.
     * @param array|string $classes
     */
    public function __construct($content = false, $url = false, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('iframe'), $classes));
        $this->content = $content;
        $this->url = $url;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        //$this->style(Tools::css('div.iframe', '', 'all: initial'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<iframe class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->url ? ' src="' . $this->url . '"' : '') .
            ($this->content ? ' srcdoc="' .
                str_replace("\"", "'",
                    ($this->content instanceof View ? $this->content->generateSafe() : $this->content)) . '"' : '') .
            '>';
        /*if ($this->url) {
            return '<iframe class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
                'src="' . $this->url . '"' . '>';
        }
        return '<div class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">' .
            ($this->content instanceof View ? $this->content->generateSafe() : $this->content);*/
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        if ($this->url) return '</iframe>';
        return '</div>';
    }

    // Short cuts

}
?>