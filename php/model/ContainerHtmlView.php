<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ContainerHtmlView
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
        $this->style(Tools::css('.container', '.gap', 'gap: 5px'));
        $this->style(Tools::css('.container', '.gap10', 'gap: 10px'));
        $this->style(Tools::css('.container', '.gap15', 'gap: 15px'));
        $this->style(Tools::css('.container', '.gap20', 'gap: 20px'));
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
}
?>