<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class DashboardHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DashboardHtmlView extends HtmlView {
    /**
     * DashboardHtmlView constructor.
     * @param array $classes
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('dashboard'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex', 'flex-wrap: wrap',
            'width: 100%', 'justify-content: space-around'));

        $this->style(Tools::css($coreSelector, array(' > ', '.dashboard-item'), 'position: relative',
            'width: 45%', 'height: 0', 'padding-bottom: 45%', 'box-sizing: border-box', 'margin-top: 2.5%',
            'margin-bottom: 2.5%', 'overflow: hidden', 'border: solid 1px rgba(200, 200, 200, 1.0)',
            'cursor: pointer', '-webkit-box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 0.3)',
            '-moz-box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 0.3)', 'box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 0.3)'));

        $this->style(Tools::css($coreSelector, array(' > ', '.dashboard-item', ' > ', ':last-child'),
            'width: 100%', 'margin-bottom: -100%'));

        $this->style(Tools::css($coreSelector, array(' > ', '.dashboard-item.plus'), 'display: flex',
            'align-items: center', 'justify-content: center', 'background: rgba(170, 170, 170, 1.0)',
            'opacity: 0.5', 'transition: opacity 1s'));

        $this->style(Tools::css($coreSelector, array(' > ', '.dashboard-item.plus', ' > ', '.plus'),
            'display: inline-block', 'width: 30px', 'height: 30px', 'padding: 6px', 'box-sizing: border-box',
                     'background: linear-gradient(rgba(255, 255, 255, 1), rgba(255, 255, 255, 1)) content-box,
                        linear-gradient(rgba(255, 255, 255, 1), rgba(255, 255, 255, 1)) content-box,
                        rgba(255,255,255,0)', 'background-position: center', 'background-size: 100% 5px, 5px 100%',
                     'background-repeat: no-repeat', 'width: 100px', 'height: 100px'));

        $this->style(Tools::css($coreSelector, array(' > ', '.dashboard-item.plus', ':hover'),
                    'opacity: 1.0'));
    }

    // Short cuts

    /**
     * Create a header.
     * @param array $classes A set of classes.
     * @return DashboardHeaderHtmlView
     */
    public function header(array $classes = array()) {
        $headerView = new DashboardHeaderHtmlView($classes);
        $this->add($headerView);
        return $headerView;
    }

    /**
     * Create an item.
     * @param array $classes A set of classes.
     * @return DashboardItemHtmlView
     */
    public function item(array $classes = array()) {
        $itemView = new DashboardItemHtmlView($classes);
        $this->add($itemView);
        return $itemView;
    }

    /**
     * Add a plus dashboard item.
     * @param array $classes Additional classes.
     * @return DashboardItemHtmlView
     */
    public function plus(array $classes = array()) {
        $itemView = new DashboardItemHtmlView(array('plus') + $classes);
        $itemView->add(new PlusHtmlView());
        $this->add($itemView);
        return $itemView;
    }

    /**
     * Return a new container html view.
     * @param array $classes Classes.
     * @return DashboardHtmlView
     */
    public static function _new(array $classes) {
        return new DashboardHtmlView($classes);
    }
}
?>