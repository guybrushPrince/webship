<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ContainerHtmlView.
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ContainerHtmlView extends HtmlView {

    /**
     * ContainerHtmlView constructor.
     * @param string[]|string $classes
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['container'], $classes));

        $this->style(Tools::css('.container', '', 'display: flex',
            'flex-wrap: wrap', 'align-items: flex-start', 'align-content: flex-start', 'width: 100%',
            'box-sizing: border-box'));
        $this->style(Tools::css('.container', '.ver', 'flex-direction: column'));
        $this->style(Tools::css('.container', '.hor', 'flex-direction: row'));
        $this->style(Tools::css('.container', '.single', 'flex-wrap: nowrap'));
        $this->style(Tools::css('.container', '.vcent', 'align-items: center',
            'align-content: center'));
        $this->style(Tools::css('.container', '.scroll-x', 'overflow-x: auto'));
        $this->style(Tools::css('.container', '.scroll-y', 'overflow-y: auto'));
        $this->style(Tools::css('.container', '.gap', 'gap: 5px'));
        $this->style(Tools::css('.container', '.gap5', 'gap: 5px'));
        $this->style(Tools::css('.container', '.gap10', 'gap: 10px'));
        $this->style(Tools::css('.container', '.gap15', 'gap: 15px'));
        $this->style(Tools::css('.container', '.gap20', 'gap: 20px'));
        $this->style(Tools::css('.container', '.p', 'padding: 5px'));
        $this->style(Tools::css('.container', '.p10', 'padding: 10px'));
        $this->style(Tools::css('.container', '.p15', 'padding: 15px'));
        $this->style(Tools::css('.container', '.p20', 'padding: 20px'));
        $this->style(Tools::css('.container', '.hcent', 'justify-content: center',
            'justify-items: center'));
        $this->style(Tools::css('.container', '.hend', 'justify-content: flex-end',
            'justify-items: flex-end'));
        $this->style(Tools::css('.container', '.vbot', 'align-items: flex-end'));
        $this->style(Tools::css('.container', '.h100', 'height: 100%'));
        $this->style(Tools::css('.container', '.rel', 'position: relative'));
        $this->style(Tools::css('.container', ['.self-fit'], 'width: -moz-fit-content',
            'width: fit-content'));
        $this->style(Tools::css('.container', ['.fit', ' > ', '*'], 'width: -moz-fit-content',
            'width: fit-content'));
        $this->style(Tools::css('.container', [' > ', '.container-item', '.ver'], 'flex-direction: column'));
        $this->style(Tools::css('.container', [' > ', '.container-item', '.hor'], 'flex-direction: row'));
        $this->style(Tools::css('.container.c100', [' > ', '.container-item'], 'width: 100%'));

        $this->style(Tools::css('.container.short-hint', [], 'display: inline',
            'opacity: 0.6', 'font-size: 10px'));

        $this->style(Tools::css('.container.with-default', [' > ', '.default:first-child:last-child'],
            'display: inherit'));
        $this->style(Tools::css('.container.with-default', [' > ', '.default'],
            'display: none'));
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
        $this->addClass('padding' . $padding);
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