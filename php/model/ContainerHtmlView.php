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
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('container'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex',
            'flex-wrap: wrap', 'align-items: flex-start', 'align-content: flex-start', 'width: 100%',
            'box-sizing: border-box'));
        $this->style(Tools::css('.container', '.ver', 'flex-direction: column'));
        $this->style(Tools::css('.container', '.hor', 'flex-direction: row'));
        $this->style(Tools::css('.container', '.single', 'flex-wrap: nowrap'));
        $this->style(Tools::css('.container', '.vcent', 'align-items: center'));
        $this->style(Tools::css('.container', '.gap', 'gap: 5px'));
        $this->style(Tools::css('.container', '.gap10', 'gap: 10px'));
        $this->style(Tools::css('.container', '.gap15', 'gap: 15px'));
        $this->style(Tools::css('.container', '.gap20', 'gap: 20px'));
        $this->style(Tools::css('.container', '.hcent', 'justify-content: center'));
        $this->style(Tools::css('.container', '.vbot', 'align-items: flex-end'));
        $this->style(Tools::css('.container', ['.fit', ' > ', '*'], 'width: fit-content'));
        $this->style(Tools::css('.container', [' > ', '.container-item', '.ver'], 'flex-direction: column'));
        $this->style(Tools::css('.container', [' > ', '.container-item', '.hor'], 'flex-direction: row'));
    }

    // Short cuts

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