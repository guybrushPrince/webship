<?php

namespace webship\containers;

use ship\views\TextView;
use webship\core\HtmlView;
use webship\WebshipTools;

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
 * @examples
 *     $container = ContainerHtmlView::createIt(['hor', 'vcent', 'gap10']);
 *     $container->add(ColorHtmlView::createIt('#0000FF'))->texts('Blue');
 *     $container->add(ColorHtmlView::createIt('#FF0000'))->texts('Red');
 *     $container->add(ColorHtmlView::createIt('#00FF00'))->texts('Green');
 */
class ContainerHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     * @param string|null $color An optional background color.
     * @param string|null $fontColor An optional font color.
     */
    public function __construct(array|string $classes = [], string|null $color = null, string|null $fontColor = null) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['container'], $classes));

        self::addCSS();

        if (!is_null($color)) {
            $this->localStyle('background: ' . $color);
            if (!is_null($fontColor)) {
                $this->localStyle('color: ' . $fontColor);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.container', '', 'display: flex',
            'flex-wrap: wrap', 'align-items: flex-start', 'align-content: flex-start', 'width: 100%',
            'box-sizing: border-box'));
        self::addStyle(WebshipTools::css('.container', '.cb', 'box-sizing: content-box'));
        self::addStyle(WebshipTools::css('.container', '.ver', 'flex-direction: column'));
        self::addStyle(WebshipTools::css('.container', '.hor', 'flex-direction: row'));
        self::addStyle(WebshipTools::css('.container', '.rev-ver', 'flex-direction: column-reverse'));
        self::addStyle(WebshipTools::css('.container', '.rev-hor', 'flex-direction: row-reverse'));
        self::addStyle(WebshipTools::css('.container', '.single', 'flex-wrap: nowrap'));
        self::addStyle(WebshipTools::css('.container', '.revert', 'flex-wrap: revert'));
        self::addStyle(WebshipTools::css('.container', '.vcent', 'align-items: center',
            'align-content: center'));
        self::addStyle(WebshipTools::css('.container', '.vbot', 'align-items: flex-end',
            'align-content: flex-end'));
        self::addStyle(WebshipTools::css('.container', '.initial', 'align-items: initial',
            'align-content: initial'));
        self::addStyle(WebshipTools::css('.container', '.scroll-x', 'overflow-x: auto'));
        self::addStyle(WebshipTools::css('.container', '.scroll-y', 'overflow-y: auto'));

        self::addStyle(WebshipTools::css('.container', '.gap', 'gap: 5px'));
        self::addStyle(WebshipTools::css('.container', '.gap5', 'gap: 5px'));
        self::addStyle(WebshipTools::css('.container', '.gap10', 'gap: 10px'));
        self::addStyle(WebshipTools::css('.container', '.gap15', 'gap: 15px'));
        self::addStyle(WebshipTools::css('.container', '.gap20', 'gap: 20px'));

        self::addStyle(WebshipTools::css('.container', '.p', 'padding: 5px'));
        self::addStyle(WebshipTools::css('.container', '.p0', 'padding: 0'));
        self::addStyle(WebshipTools::css('.container', '.p0i', 'padding: 0 !important'));
        self::addStyle(WebshipTools::css('.container', '.p5', 'padding: 5px'));
        self::addStyle(WebshipTools::css('.container', '.p10', 'padding: 10px'));
        self::addStyle(WebshipTools::css('.container', '.p15', 'padding: 15px'));
        self::addStyle(WebshipTools::css('.container', '.p20', 'padding: 20px'));

        self::addStyle(WebshipTools::css('.container', '.pb5i', 'padding-bottom: 5px !important'));
        self::addStyle(WebshipTools::css('.container', '.ptb5i', 'padding-top: 5px !important',
            'padding-bottom: 5px !important'));

        self::addStyle(WebshipTools::css('.container', '.ptb', 'padding-top: 5px',
            'padding-bottom: 5px'));
        self::addStyle(WebshipTools::css('.container', '.ptb5', 'padding-top: 5px',
            'padding-bottom: 5px'));
        self::addStyle(WebshipTools::css('.container', '.ptb10', 'padding-top: 10px',
            'padding-bottom: 10px'));
        self::addStyle(WebshipTools::css('.container', '.ptb15', 'padding-top: 15px',
            'padding-bottom: 15px'));
        self::addStyle(WebshipTools::css('.container', '.ptb20', 'padding-top: 20px',
            'padding-bottom: 20px'));

        self::addStyle(WebshipTools::css('.container', '.m0', 'margin: 0'));
        self::addStyle(WebshipTools::css('.container', '.m0i', 'margin: 0 !important'));
        self::addStyle(WebshipTools::css('.container', '.m5', 'margin: 5px'));
        self::addStyle(WebshipTools::css('.container', '.m10', 'margin: 10px'));
        self::addStyle(WebshipTools::css('.container', '.m15', 'margin: 15px'));
        self::addStyle(WebshipTools::css('.container', '.m20', 'margin: 20px'));

        self::addStyle(WebshipTools::css('.container', '.hcent', 'justify-content: center',
            'justify-items: center'));
        self::addStyle(WebshipTools::css('.container', '.hend', 'justify-content: flex-end',
            'justify-items: flex-end'));
        self::addStyle(WebshipTools::css('.container', '.h100', 'height: 100%'));
        self::addStyle(WebshipTools::css('.container', '.w50', 'width: 50%'));
        self::addStyle(WebshipTools::css('.container', '.w33', 'width: calc(100% / 3)'));
        self::addStyle(WebshipTools::css('.container', '.w25', 'width: 25%'));
        self::addStyle(WebshipTools::css('.container', '.rel', 'position: relative'));
        self::addStyle(WebshipTools::css('.container', ['.self-fit'], 'width: -moz-fit-content',
            'width: fit-content'));
        self::addStyle(WebshipTools::css('.container', ['.fit', ' > ', '*'], 'width: -moz-fit-content',
            'width: fit-content'));
        self::addStyle(WebshipTools::css('.container', [' > ', '.container-item', '.ver'], 'flex-direction: column'));
        self::addStyle(WebshipTools::css('.container', [' > ', '.container-item', '.hor'], 'flex-direction: row'));
        self::addStyle(WebshipTools::css('.container.c100', [' > ', '.container-item'], 'width: 100%'));

        self::addStyle(WebshipTools::css('.container.short-hint', [], 'display: inline',
            'opacity: 0.6', 'font-size: 10px'));

        self::addStyle(WebshipTools::css('.container.with-default', [' > ', '.default:first-child:last-child'],
            'display: inherit'));
        self::addStyle(WebshipTools::css('.container.with-default', [' > ', '.default'],
            'display: none'));

        self::addStyle(WebshipTools::css('.container.gap5', [' > ', '.container.w50'],
            'width: calc(50% - 2.5px)'));
        self::addStyle(WebshipTools::css('.container.gap10', [' > ', '.container.w50'],
            'width: calc(50% - 5px)'));
        self::addStyle(WebshipTools::css('.container.gap15', [' > ', '.container.w50'],
            'width: calc(50% - 7.5px)'));
        self::addStyle(WebshipTools::css('.container.gap20', [' > ', '.container.w50'],
            'width: calc(50% - 10px)'));

        self::addStyle(WebshipTools::css('.container', '.br0', 'border-radius: 0'));
        self::addStyle(WebshipTools::css('.container', '.br5', 'border-radius: 5px'));
        self::addStyle(WebshipTools::css('.container', '.br10', 'border-radius: 10px'));
        self::addStyle(WebshipTools::css('.container', '.br15', 'border-radius: 15px'));
        self::addStyle(WebshipTools::css('.container', '.br20', 'border-radius: 20px'));
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