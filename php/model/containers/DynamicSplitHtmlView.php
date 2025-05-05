<?php

class DynamicSplitHtmlView extends ContainerHtmlView {

    private HtmlView $leftColumn;

    private HtmlView $rightColumn;

    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes = array_merge($classes, ['splitting', 'hor']);
        parent::__construct($classes);

        self::addCSS();
    }

    public static function addCSS() : void {
        self::addStyle(Tools::css('.splitting', [], '--left-w: 50%', '--right-w: 50%',
            '--right-w-h: var(--right-w)', 'position: relative'));
        self::addStyle(Tools::css('.splitting', [' > ', '.left'], 'width: var(--left-w)',
            'transition: width 0.5s'));
        self::addStyle(Tools::css('.splitting.dynamic', [' > ', '.right'],
            'position: absolute', 'right: 0', 'background: var(--neutral)'));
        self::addStyle(Tools::css('.splitting.dynamic', [' > ', '.right:hover'],
            'position: absolute', 'width: var(--right-w-h)'));
        self::addStyle(Tools::css('.splitting', [' > ', '.left.extended'], 'width: 100%'));
        self::addStyle(Tools::css('.splitting', [' > ', '.left', ' > ', '*'], 'width: 100%'));
        self::addStyle(Tools::css('.splitting', [' > ', '.right'], 'height: 100%',
            'width: var(--right-w)', 'transition: width 0.5s'));
        self::addStyle(Tools::css('.splitting', [' > ', '.right', ' > ', '*'],'height: 100%',
            'width: 100%'));
        self::addStyle(Tools::css('.splitting', [' > ', '.right', ' > ', '*', ' > ', '*'],
            'height: 100%'));
        self::addStyle(Tools::css('.container-item.select-view', '', 'width: 100%'));
        self::addStyle(Tools::css('.panel-item.select-view', '', 'width: 100%'));
        self::addStyle(Tools::css('.select-view.invisible', [], 'display: none !important'));
    }

    public static function create(?int $leftSize = null, ?int $hoverRightSize = null,
                                  array|string $classes = []) : DynamicSplitHtmlView {
        $view = new DynamicSplitHtmlView($classes);

        $leftColumn = $view->item('left');
        $rightColumn = $view->item('right');

        if ($hoverRightSize) {
            $view->addClass('dynamic');
            $view->localStyle('--right-w-h: ' . $hoverRightSize . '%');
        }

        if ($leftSize) {
            $view->localStyle('--left-w: ' . $leftSize . '%');
            $view->localStyle('--right-w: ' . (100 - $leftSize) . '%');
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