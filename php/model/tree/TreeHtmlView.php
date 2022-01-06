<?php

/**
 * Class TreeHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TreeHtmlView extends HtmlView {

    /**
     * MenuHtmlView constructor.
     * @param string[]|string $classes
     */
    public function __construct($classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(['tree'], $classes));
        $this->ref('tree');

        $this->style(Tools::css('ul.tree', '', 'padding-top: 0', 'padding-bottom: 0'));
        $this->style(Tools::css('ul.tree', [' > ', 'li', ' ', 'a'], 'text-align: left',
            'justify-content: flex-start'));
        $this->style(Tools::css('ul.tree', [' > ', 'li'], 'min-height: 20px', 'list-style-type: none'));
        $this->style(Tools::css('ul.tree', [' > ', 'li:not(:last-child)'],
            'border-bottom: 1px solid rgba(220, 220, 220, 1.0)'));
        $this->style(Tools::css('ul.tree', [' > ', 'li.entry'], 'margin-left: -40px'));
        $this->style(Tools::css('ul.tree', [' > ', 'li.sub'], 'margin-left: -40px', 'cursor: pointer'));
        $this->style(Tools::css('ul.tree:not(.open)', [' > ', 'li.sub'],
            'list-style-type: disclosure-closed', 'border-bottom: none'));
        $this->style(Tools::css('ul.tree:not(.open)', [' > ', 'li.sub',  ' ~ ', 'li'],
            'display: none'));
        $this->style(Tools::css('ul.tree.open', [' > ', 'li.sub'],
            'list-style-type: disclosure-open'));
        $this->style(Tools::css('ul.tree.open', [' > ', 'li.sub',  ' ~ ', 'li'], 'display: inherit'));
        $this->style(Tools::css('ul.tree', [' > ', 'li', ' ', 'span[data-box]'], 'padding-left: 0',
            'display: flex', 'flex-direction: row', 'align-items: center', 'min-height: 20px'));
        $this->style(Tools::css('ul.tree', [' > ', 'li', ' ', 'span[data-box]:empty:after'],
            'content: "' . [
                Language::ENGLISH => 'no-text',
                Language::GERMAN  => 'Kein-Text'
            ][View::getLanguage()] . '"', 'display: block'));
        $this->style(Tools::css('ul.tree', [' > ', 'li.selectable'], 'transition: 0.5s',
            'cursor: pointer'));
        $this->style(Tools::css('ul.tree', [' > ', 'li.selectable:hover'],
            'background: rgba(220, 220, 220, 1.0)'));

        $this->style(Tools::css('ul.tree', [' ', '.leaf-info'], 'display: flex', 'flex-direction: row',
            'flex-wrap: nowrap', 'min-height: 20px', 'align-items: center'));
        $this->style(Tools::css('ul.tree', [' ', '.leaf-info', ' > ', '.arrow'],'display: flex',
            'flex-direction: row', 'width: 15px', 'min-width: 15px', 'height: 15px', 'border-radius: 50%',
            'background: rgba(200, 200, 200, 1.0)', 'justify-content: center', 'align-items: center',
            'margin-left: 10px', 'transition: 0.5s', 'cursor: pointer'));
        $this->style(Tools::css('ul.tree', [' ', '.leaf-info', ' > ', '.arrow:hover'],
            'background: rgba(150, 150, 150, 1.0)'));
        $this->style(Tools::css('ul.tree', [' ', '.leaf-info', ' > ', '.arrow', ' > ', 'img'],
            'width: 10px', 'height: 10px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<ul class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '<script>' .
            '$("' . $this->idc() . '").children("li.sub").click((e) => { $(e.currentTarget).parent().toggleClass("open")});' .
            '$("' . $this->idc() . '").children("script").remove();' .
            '</script></ul>';
    }

    /**
     * Creates a new tree.
     * @param string|string[] $classes Some additional classes.
     * @return TreeHtmlView
     */
    public static function create($classes = []) : TreeHtmlView {
        return new TreeHtmlView($classes);
    }

    /**
     * Create and add a leaf.
     * @param string|string[] $classes Some additional classes.
     * @return TreeLeafHtmlView
     */
    public function leaf($classes = []) : TreeLeafHtmlView {
        $leaf = new TreeLeafHtmlView($classes);
        $this->add($leaf);
        return $leaf;
    }

    /**
     * Creates an entry leaf.
     * @param string|string[] $classes Some additional classes.
     * @return TreeLeafHtmlView
     */
    public function entryLeaf($classes = []) : TreeLeafHtmlView {
        return $this->leaf(array_merge(['entry'], (is_array($classes) ? $classes : [$classes])));
    }

    /**
     * Creates a sub tree leaf.
     * @param string|string[] $classes Some additional classes.
     * @return TreeLeafHtmlView
     */
    public function subtree($classes = []) : TreeLeafHtmlView {
        return $this->leaf(array_merge(['sub'], (is_array($classes) ? $classes : [$classes])));
    }

}
?>