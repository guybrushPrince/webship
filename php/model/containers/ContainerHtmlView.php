<?php

/**
 * View ContainerHtmlView.
 *
 * <p>A <em>container</em> represents the simplest but also one of the most powerful Webship views.
 * It is able to arrange many things, place a gap in between views, vertical align texts, etc.</p>
 *
 * <img src="http://localhost/coast/webship/modeldoc/container.png" />
 *
 * <code>
 * $container = ContainerHtmlView::createIt(['hor', 'vcent', 'gap10']);
 * $container->add(ColorHtmlView::createIt('#0000FF'))->texts('Blue');
 * $container->add(ColorHtmlView::createIt('#FF0000'))->texts('Red');
 * $container->add(ColorHtmlView::createIt('#00FF00'))->texts('Green');
 * </code>
 *
 * @package webship
 * @subpackge php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 */
class ContainerHtmlView extends HtmlView {

    /**
     * ContainerHtmlView constructor.
     * @param string[]|string $classes
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['container'], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.container', '', 'display: flex',
            'flex-wrap: wrap', 'align-items: flex-start', 'align-content: flex-start', 'width: 100%',
            'box-sizing: border-box'));
        self::addStyle(Tools::css('.container', '.ver', 'flex-direction: column'));
        self::addStyle(Tools::css('.container', '.hor', 'flex-direction: row'));
        self::addStyle(Tools::css('.container', '.single', 'flex-wrap: nowrap'));
        self::addStyle(Tools::css('.container', '.vcent', 'align-items: center',
            'align-content: center'));
        self::addStyle(Tools::css('.container', '.scroll-x', 'overflow-x: auto'));
        self::addStyle(Tools::css('.container', '.scroll-y', 'overflow-y: auto'));
        self::addStyle(Tools::css('.container', '.gap', 'gap: 5px'));
        self::addStyle(Tools::css('.container', '.gap5', 'gap: 5px'));
        self::addStyle(Tools::css('.container', '.gap10', 'gap: 10px'));
        self::addStyle(Tools::css('.container', '.gap15', 'gap: 15px'));
        self::addStyle(Tools::css('.container', '.gap20', 'gap: 20px'));
        self::addStyle(Tools::css('.container', '.p', 'padding: 5px'));
        self::addStyle(Tools::css('.container', '.p5', 'padding: 5px'));
        self::addStyle(Tools::css('.container', '.p10', 'padding: 10px'));
        self::addStyle(Tools::css('.container', '.p15', 'padding: 15px'));
        self::addStyle(Tools::css('.container', '.p20', 'padding: 20px'));
        self::addStyle(Tools::css('.container', '.hcent', 'justify-content: center',
            'justify-items: center'));
        self::addStyle(Tools::css('.container', '.hend', 'justify-content: flex-end',
            'justify-items: flex-end'));
        self::addStyle(Tools::css('.container', '.vbot', 'align-items: flex-end'));
        self::addStyle(Tools::css('.container', '.h100', 'height: 100%'));
        self::addStyle(Tools::css('.container', '.w50', 'width: 50%'));
        self::addStyle(Tools::css('.container', '.rel', 'position: relative'));
        self::addStyle(Tools::css('.container', ['.self-fit'], 'width: -moz-fit-content',
            'width: fit-content'));
        self::addStyle(Tools::css('.container', ['.fit', ' > ', '*'], 'width: -moz-fit-content',
            'width: fit-content'));
        self::addStyle(Tools::css('.container', [' > ', '.container-item', '.ver'], 'flex-direction: column'));
        self::addStyle(Tools::css('.container', [' > ', '.container-item', '.hor'], 'flex-direction: row'));
        self::addStyle(Tools::css('.container.c100', [' > ', '.container-item'], 'width: 100%'));

        self::addStyle(Tools::css('.container.short-hint', [], 'display: inline',
            'opacity: 0.6', 'font-size: 10px'));

        self::addStyle(Tools::css('.container.with-default', [' > ', '.default:first-child:last-child'],
            'display: inherit'));
        self::addStyle(Tools::css('.container.with-default', [' > ', '.default'],
            'display: none'));
    }

    /**
     * Create a new container.
     * @param string|string[] $classes Some classes.
     * @return ContainerHtmlView
     */
    public static function createIt(string|array $classes = []) : ContainerHtmlView {
        return self::_new($classes);
    }

    ///////////////////////////
    // Shortcuts
    ///////////////////////////

    /**
     * Create a header.
     * @param string[]|string $classes A set of classes.
     * @return HtmlView|TextView
     */
    public function header(string|array $classes = []) : HtmlView|TextView {
        $headerView = new ContainerHeaderHtmlView($classes);
        $this->add($headerView);
        return $headerView;
    }

    /**
     * Create an item.
     * @param string[]|string $classes A set of classes.
     * @return HtmlView
     */
    public function item(string|array $classes = []) : HtmlView {
        $itemView = new ContainerItemHtmlView($classes);
        $this->add($itemView);
        return $itemView;
    }

    /**
     * Return a new container html view.
     * @param array|string $classes Classes.
     * @return ContainerHtmlView
     */
    public static function _new(string|array $classes = []) : static {
        return new ContainerHtmlView($classes);
    }

    /**
     * Set a vertical orientation.
     * @return $this
     */
    public function vertical() : static {
        $this->addClass('ver');
        return $this;
    }

    /**
     * Set a horizontal orientation.
     * @return $this
     */
    public function horizontal() : static {
        $this->addClass('hor');
        return $this;
    }

    /**
     * Do not allow multiple rows/columns.
     * @return $this
     */
    public function one() : static {
        $this->addClass('single');
        return $this;
    }

    /**
     * Align the items vertically centered.
     * @return $this
     */
    public function centerVertical() : static {
        $this->addClass('vcent');
        return $this;
    }

    /**
     * Align the items horizontally centered.
     * @return $this
     */
    public function centerHorizontal() : static {
        $this->addClass('hcent');
        return $this;
    }

    /**
     * Set the gap between the elements of the container.
     * @param int $gapSize The size of the gap.
     * @return $this
     */
    public function gap(int $gapSize) : static {
        $this->addClass('gap' . $gapSize);
        return $this;
    }

    /**
     * Set the padding of the container.
     * @param int $padding The padding.
     * @return $this
     */
    public function padding(int $padding) : static {
        $this->addClass('p' . $padding);
        return $this;
    }

    /**
     * Allow the container to scroll horizontally if necessary.
     * @return $this
     */
    public function scrollHorizontal() : static {
        $this->addClass('scroll-x');
        return $this;
    }

    /**
     * Allow the container to scroll vertically if necessary.
     * @return $this
     */
    public function scrollVertical() : static {
        $this->addClass('scroll-y');
        return $this;
    }

    /**
     * Set the height to 100% (full height).
     * @return $this
     */
    public function fullHeight() : static {
        $this->addClass('h100');
        return $this;
    }

    /**
     * Set the width to 100% (full width).
     * @return $this
     */
    public function fullWidth() : static {
        $this->addClass('w100');
        return $this;
    }
}
?>