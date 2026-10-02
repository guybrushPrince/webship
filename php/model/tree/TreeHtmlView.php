<?php

namespace webship\trees;

use webship\interoperability\WEvent;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View TreeHtmlView.
 *
 * <p>Displays a tree with entries and collapsable subtrees.</p>
 *
 * <code>
 * $tree = TreeHtmlView::create(['open']);
 *
 * $readme = $tree->entryLeaf(['selectable']);
 * $readme->texts('README.md');
 *
 * $srcTree = TreeHtmlView::create();
 * $srcHeader = $srcTree->subtree();
 * $srcHeader->texts('src/');
 * $ctrl = $srcTree->entryLeaf(['selectable']);
 * $ctrl->texts('Controller.php');
 * $model = $srcTree->entryLeaf(['selectable']);
 * $model->texts('Model.php');
 * $leafSrc = $tree->leaf();
 * $leafSrc->add($srcTree);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/tree.png" />
 *
 * @title Tree
 * @category tree
 *
 * @package webship
 * @subpackage php/model/tree
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $tree = TreeHtmlView::create(['open']);
 *     $readme = $tree->entryLeaf(['selectable']);
 *     $readme->texts('README.md');
 *     $srcTree = TreeHtmlView::create();
 *     $srcHeader = $srcTree->subtree();
 *     $srcHeader->texts('src/');
 *     $ctrl = $srcTree->entryLeaf(['selectable']);
 *     $ctrl->texts('Controller.php');
 *     $model = $srcTree->entryLeaf(['selectable']);
 *     $model->texts('Model.php');
 *     $leafSrc = $tree->leaf();
 *     $leafSrc->add($srcTree);
 */
class TreeHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes
     */
    private function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['tree'], $classes));
        $this->ref('tree');

        self::addCSS();

    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('ul.tree', '', 'padding-top: 0', 'padding-bottom: 0'));
        self::addStyle(WebshipTools::css('ul.tree', [' > ', 'li', ' ', 'a'], 'text-align: left',
            'justify-content: flex-start'));
        self::addStyle(WebshipTools::css('ul.tree', [' > ', 'li'], 'min-height: 20px',
            'list-style-type: none', '--ws-tree-indent: -40px'));
        self::addStyle(WebshipTools::css('ul.tree', [' > ', 'li:not(:last-child)'],
            'border-bottom: 1px solid var(--ws-tree-brd)'));
        self::addStyle(WebshipTools::css('ul.tree', [' > ', 'li.entry'],
            'margin-left: var(--ws-tree-indent)'));
        self::addStyle(WebshipTools::css('ul.tree', [' > ', 'li.sub'],
            'margin-left: var(--ws-tree-indent)', 'cursor: pointer'));
        self::addStyle(WebshipTools::css('ul.tree:not(.open)', [' > ', 'li.sub'],
            'list-style-type: disclosure-closed', 'border-bottom: none'));
        self::addStyle(WebshipTools::css('ul.tree:not(.open)', [' > ', 'li.sub', ' ~ ', 'li'],
            'display: none'));
        self::addStyle(WebshipTools::css('ul.tree.open', [' > ', 'li.sub'],
            'list-style-type: disclosure-open'));
        self::addStyle(WebshipTools::css('ul.tree.open', [' > ', 'li.sub', ' ~ ', 'li'], 'display: inherit'));
        self::addStyle(WebshipTools::css('ul.tree', [' > ', 'li', ' ', 'span.default'], 'padding-left: 0',
            'display: flex', 'flex-direction: row', 'align-items: center', 'min-height: 20px', 'font-style: italic'));
        self::addStyle(WebshipTools::css('ul.tree', [' > ', 'li.selectable'], 'transition: 0.5s',
            'cursor: pointer'));
        self::addStyle(WebshipTools::css('ul.tree', [' > ', 'li.selectable:hover'],
            'background: var(--ws-tree-brd)'));

        self::addStyle(WebshipTools::css('ul.tree', [' ', '.leaf-info'], 'display: flex', 'flex-direction: row',
            'flex-wrap: nowrap', 'min-height: 20px', 'align-items: center'));
        self::addStyle(WebshipTools::css('ul.tree', [' ', '.leaf-info', ' > ', '.arrow'], 'display: flex',
            'flex-direction: row', 'width: 15px', 'min-width: 15px', 'height: 15px', 'border-radius: 50%',
            'background: var(--ws-tree-arrow)', 'justify-content: center', 'align-items: center',
            'margin-left: 10px', 'transition: 0.5s', 'cursor: pointer'));
        self::addStyle(WebshipTools::css('ul.tree', [' ', '.leaf-info', ' > ', '.arrow:hover'],
            'background: var(--ws-tree-hvr)'));
        self::addStyle(WebshipTools::css('ul.tree', [' ', '.leaf-info', ' > ', '.arrow', ' > ', 'img'],
            'width: 10px', 'height: 10px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<ul class="' . implode(' ', array_merge($this->classes, [$this->id()])) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '<script>' .
            '$("' . $this->idc() . '").children("li.sub").click((e) => { ' .
            'let p = $(e.currentTarget).parent();' .
            'if (p.children().length <= 1) p.trigger((p.hasClass("open") ? "' . WEvent::CLOSE_LIST . '" : "' . WEvent::OPEN_LIST . '"));' .
            'p.toggleClass("open");' .
            '});' .
            '$("' . $this->idc() . '").children("script").remove();' .
            '</script></ul>';
    }

    /**
     * Creates a new tree.
     * @param string|string[] $classes Some additional classes.
     * @return TreeHtmlView
     */
    public static function create(array|string $classes = []) : TreeHtmlView {
        return new TreeHtmlView($classes);
    }

    /**
     * Create and add a leaf.
     * @param string|string[] $classes Some additional classes.
     * @return TreeLeafHtmlView
     */
    public function leaf(array|string $classes = []) : TreeLeafHtmlView {
        $leaf = TreeLeafHtmlView::create($classes);
        $this->add($leaf);
        return $leaf;
    }

    /**
     * Creates an entry leaf.
     * @param string|string[] $classes Some additional classes.
     * @return TreeLeafHtmlView
     */
    public function entryLeaf(array|string $classes = []) : TreeLeafHtmlView {
        return $this->leaf(array_merge(['entry'], (is_array($classes) ? $classes : [$classes])));
    }

    /**
     * Creates a sub tree leaf.
     * @param string|string[] $classes Some additional classes.
     * @return TreeLeafHtmlView
     */
    public function subtree(array|string $classes = []) : TreeLeafHtmlView {
        return $this->leaf(array_merge(['sub'], (is_array($classes) ? $classes : [$classes])));
    }
}
?>