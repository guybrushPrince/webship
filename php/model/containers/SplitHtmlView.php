<?php

/**
 * Class SplitHtmlView.
 *
 * @package webship
 * @subpackge php/model/containers
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

    /**
     * Constructor.
     * @param int $leftSize The size of the left split view.
     * @param int|null $rightSize The size of the right split view (if null, it is computed from 100).
     * @param int $leftGap The gap between the left view and the separator.
     * @param int|null $rightGap The gap between the right view and the separator (if null, it is equal to $leftGap.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(int $leftSize = 50, ?int $rightSize = null, int $leftGap = 0, ?int $rightGap = null,
                                array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'std-split';
        parent::__construct($classes);

        if (is_null($rightSize)) $rightSize = 100 - $leftSize;

        $leftSizeRatio = round($leftSize / ($leftSize + $rightSize), 2) * 100;
        $rightSizeRatio = round($rightSize / ($leftSize + $rightSize), 2) * 100;
        if (is_null($rightGap)) $rightGap = $leftGap;

        $this->left = new HtmlView();
        $this->separator = new HtmlView();
        $this->right = new HtmlView();

        $this->add($this->left);
        $this->add($this->separator);
        $this->add($this->right);

        $this->localStyle([
            '--separator-distance-left: ' . $leftGap . 'px',
            '--separator-distance-right: ' . $rightGap . 'px',
            '--separator-left-ratio: ' . $leftSizeRatio . '%',
            '--separator-right-ratio: ' . $rightSizeRatio . '%'
        ]);

        self::addCSS();
    }

    /**
     * Generates the CSS code.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.std-split', [], 'width: 100%', 'padding: 0',
            'box-sizing: border-box', 'position: relative', 'display: flex', 'flex-direction: row',
            'flex-wrap: nowrap', '--separator-width: 5px', '--separator-color: var(--light-system-background)',
        ));
        self::addStyle(Tools::css('.std-split.scroll', [], 'height: 100%'));
        self::addStyle(Tools::css('.std-split', [' > ', 'div:nth-child(1)'],
            'width: calc(var(--separator-left-ratio) - var(--separator-width))',
            'padding-right: var(--separator-distance-left)', 'box-sizing: border-box'));
        self::addStyle(Tools::css('.std-split.scroll', [' > ', 'div:nth-child(1)'],
            'height: 100%', 'overflow-y: auto'));
        self::addStyle(Tools::css('.std-split', [' > ', 'div:nth-child(2)'],
            'width: var(--separator-width)',
            'box-sizing: border-box', 'border-left: var(--separator-width) solid var(--separator-color)'));
        self::addStyle(Tools::css('.std-split', [' > ', 'div:nth-child(3)'],
            'width: var(--separator-right-ratio)',
            'padding-left: var(--separator-distance-right)', 'box-sizing: border-box'));
        self::addStyle(Tools::css('.std-split.scroll', [' > ', 'div:nth-child(3)'],
            'height: 100%', 'overflow-y: auto'));
    }

    /**
     * Creates the view.
     * @param int $leftSize The size of the left split view.
     * @param int|null $rightSize The size of the right split view (if null, it is computed from 100).
     * @param int $leftGap The gap between the left view and the separator.
     * @param int|null $rightGap The gap between the right view and the separator (if null, it is equal to $leftGap.
     * @param string[]|string $classes Some classes.
     * @return SplitHtmlView
     */
    public static function create(int $leftSize = 50, ?int $rightSize = null, int $leftGap = 0, ?int $rightGap = null,
                                  array|string $classes = []) : SplitHtmlView {
        return new SplitHtmlView($leftSize, $rightSize, $leftGap, $rightGap, $classes);
    }

    /**
     * Get the left view.
     * @return HtmlView
     */
    public function getLeft() : HtmlView {
        return $this->left;
    }

    /**
     * Get the right view.
     * @return HtmlView
     */
    public function getRight() : HtmlView {
        return $this->right;
    }

    /**
     * Get the separator view.
     * @return HtmlView
     */
    public function getSeparator() : HtmlView {
        return $this->separator;
    }

    /**
     * Sets the height of the container to 100% with auto-scrolling.
     * @return $this
     */
    public function scroll() : static {
        $this->addClass('scroll');
        return $this;
    }

}
?>