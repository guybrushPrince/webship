<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ModalHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ModalHtmlView extends HtmlView {
    /**
     * ModalHtmlView constructor.
     * @param array $classes
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('modal', 'invisible'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->add(new ContainerHtmlView(array('modal-overlay')))->ref('overlay')->
            protocol(Event::CLICK)->
                toggleClass('invisible', $this);
        $this->add(new ContainerHtmlView(array('modal-container')));

        $this->style(Tools::css($coreSelector, '', 'display: flex', ''));
        $this->style(Tools::css('.modal.invisible', '', 'display: none !important'));
        $this->style(Tools::css('.modal', array(' > ', '.modal-overlay'), 'position: fixed',
            'top: 0', 'left: 0', 'width: 100%', 'height: 100%', 'background: rgba(0, 0, 0, 0.5)', 'z-index: 100001'));
        $this->style(Tools::css('.modal', array(' > ', '.modal-container'), 'position: fixed',
            'left: 12.5%', 'top: 10%', 'width: 75%',
            'background: rgba(255, 255, 255, 1.0)', 'z-index: 100002'));
    }

    // Short cuts

    /**
     * Create a header.
     * @param array $classes A set of classes.
     * @return ContainerHtmlView
     */
    public function header(array $classes = array()) {
        $headerView = new ContainerHtmlView($classes);
        $this->subviews[1]->add($headerView);
        return $headerView;
    }

    /**
     * Adds a new Html view.
     * @param HtmlView $add The view.
     * @return HtmlView
     */
    public function item(HtmlView $add) {
        $this->subviews[1]->add($add);
        return $add;
    }

    /**
     * Return a new container html view.
     * @param array $classes Classes.
     * @return ModalHtmlView
     */
    public static function _new(array $classes) {
        return new ModalHtmlView($classes);
    }
}
?>