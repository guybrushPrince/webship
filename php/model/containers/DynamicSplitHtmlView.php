<?php

namespace webship\containers;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View DynamicSplitHtmlView.
 *
 * <p>A container view that splits content dynamically into left and right columns.</p>
 *
 * <code>
 * $split = DynamicSplitHtmlView::create(20, 20);
 * $left = $split->getLeftColumn();
 * $right = $split->getRightColumn();
 * </code>
 *
 * @title DynamicSplit
 * @category containers
 * 
 * @package webship
 * @subpackage php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $split = DynamicSplitHtmlView::create(20, 20);
 *     $left = $split->getLeftColumn();
 *     $right = $split->getRightColumn();
 */
class DynamicSplitHtmlView extends ContainerHtmlView {

    private HtmlView $leftColumn;

    private HtmlView $rightColumn;

    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes = array_merge($classes, ['splitting', 'hor']);
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.splitting', [], '--ws-ds-left-w: 50%', '--ws-ds-right-w: 50%',
            '--ws-ds-right-w-h: var(--ws-ds-right-w)', 'position: relative'));
        self::addStyle(WebshipTools::css('.splitting', [' > ', '.left'], 'width: var(--ws-ds-left-w)',
            'transition: width 0.5s'));
        self::addStyle(WebshipTools::css('.splitting.dynamic', [' > ', '.right'],
            'position: absolute', 'right: 0', 'background: var(--ws-neutral)'));
        self::addStyle(WebshipTools::css('.splitting.dynamic', [' > ', '.right:hover'],
            'position: absolute', 'width: var(--ws-ds-right-w-h)'));
        self::addStyle(WebshipTools::css('.splitting', [' > ', '.left.extended'], 'width: 100%'));
        self::addStyle(WebshipTools::css('.splitting', [' > ', '.left', ' > ', '*'], 'width: 100%'));
        self::addStyle(WebshipTools::css('.splitting', [' > ', '.right'], 'height: 100%',
            'width: var(--ws-ds-right-w)', 'transition: width 0.5s'));
        self::addStyle(WebshipTools::css('.splitting', [' > ', '.right', ' > ', '*'], 'height: 100%',
            'width: 100%'));
        self::addStyle(WebshipTools::css('.splitting', [' > ', '.right', ' > ', '*', ' > ', '*'],
            'height: 100%'));
        self::addStyle(WebshipTools::css('.container-item.select-view', '', 'width: 100%'));
        self::addStyle(WebshipTools::css('.panel-item.select-view', '', 'width: 100%'));
        self::addStyle(WebshipTools::css('.select-view.invisible', [], 'display: none !important'));
    }

    public static function create(?int $leftSize = null, ?int $hoverRightSize = null,
                                  array|string $classes = []) : DynamicSplitHtmlView {
        $view = new DynamicSplitHtmlView($classes);

        $leftColumn = $view->item('left');
        $rightColumn = $view->item('right');

        if ($hoverRightSize) {
            $view->addClass('dynamic');
            $view->localStyle('--ws-ds-right-w-h: ' . $hoverRightSize . '%');
        }

        if ($leftSize) {
            $view->localStyle('--ws-ds-left-w: ' . $leftSize . '%');
            $view->localStyle('--ws-ds-right-w: ' . (100 - $leftSize) . '%');
        }

        $view->leftColumn = $leftColumn->container();
        $view->rightColumn = $rightColumn->container();

        return $view;
    }

    /**
     * Get the left column.
     * @return HtmlView
     */
    public function getLeftColumn() : HtmlView {
        return $this->leftColumn;
    }

    /**
     * Get the right column.
     * @return HtmlView
     */
    public function getRightColumn() : HtmlView {
        return $this->rightColumn;
    }

}
?>