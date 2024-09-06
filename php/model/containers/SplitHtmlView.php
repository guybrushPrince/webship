<?php

/**
 * Class SplitHtmlView.
 *
 * @package webship
 * @subpackge php/model/containers
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class SplitHtmlView extends HtmlView {

    /**
     * The left view.
     * @var HtmlView
     */
    private HtmlView $left;

    /**
     * The separator.
     * @var HtmlView
     */
    private HtmlView $separator;

    /**
     * The right view.
     * @var HtmlView
     */
    private HtmlView $right;

    public function __construct(int $leftSize = 50, int $rightSize = 50, int $gap = 0, array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'std-split';
        parent::__construct($classes);

        $leftSizeRatio = round($leftSize / ($leftSize + $rightSize), 2) * 100;
        $rightSizeRatio = round($rightSize / ($leftSize + $rightSize), 2) * 100;

        $this->left = new HtmlView();
        $this->separator = new HtmlView();
        $this->right = new HtmlView();

        $this->style(Tools::css('.std-split', [], 'width: 100%', 'padding: 0',
            'box-sizing: border-box', 'position: relative', 'display: flex', 'flex-direction: row',
            'flex-wrap: nowrap'));
        $this->style(Tools::css('.std-split', [' > ', 'div:nth-child(1)'],
            'width: calc(' . $leftSizeRatio . '% - )',
            'padding-right: 20px', 'box-sizing: border-box', 'border-right: 1px solid var(--light-system-background)'));
        $this->style(Tools::css('.std-split', [' > ', 'div:nth-child(2)'], 'width: ' . $leftSizeRatio . '%',
            'padding-right: 20px', 'box-sizing: border-box', 'border-right: 1px solid var(--light-system-background)'));
        $this->style(Tools::css('.std-split', [' > ', 'div:nth-child(3)'], 'width: ' . $rightSizeRatio . '%',
            'padding-left: 20px', 'box-sizing: border-box'));
    }
}
?>