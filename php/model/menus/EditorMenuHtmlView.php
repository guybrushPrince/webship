<?php

class EditorMenuHtmlView extends HtmlView {

    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'editor-menu';
        parent::__construct($classes);

        $this->style(Tools::css('.editor-menu', [], 'width: 100%', 'padding: 0', 'margin: 5px 0 0 0',
            'background: var(--login-border)', 'height: 30px', 'display: flex', 'flex-direction: row'));

        $this->style(Tools::css('.editor-menu', [' > ', '.entry'], 'font-family: Roboto, sans-serif',
            'text-transform: none', 'font-size: 12px', 'cursor: pointer', 'padding: 0 10px', 'height: 100%',
            'display: flex', 'flex-direction: row', 'align-items: center'));
        $this->style(Tools::css('.editor-menu', [' > ', '.entry', ':hover'],
            'opacity: 1', 'background: var(--main-menu)'));

    }

    public static function create(array|string $classes = []) : EditorMenuHtmlView {
        return new EditorMenuHtmlView($classes);
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return str_replace('div', 'nav', parent::generateOpeningTag());
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return str_replace('div', 'nav', parent::generateClosingTag());
    }

    public function entry(array|string|null $texts = null, string|array $classes = []) : HtmlView {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'entry';
        $entry = new HtmlView($classes);
        $this->add($entry);
        $entry->texts($texts);
        return $entry;
    }

}
?>