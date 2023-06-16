<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ModalHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ModalHtmlView extends HtmlView {
    /**
     * ModalHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('modal', 'invisible'), $classes));

        $overlay = $this->add(new ContainerHtmlView('modal-overlay'));
        $overlay->ref('overlay')->
            protocol(Event::CLICK, ['sameTarget' => true])->
                delete($this);
        $overlay->add(new ContainerHtmlView('modal-container'));

        $this->style(Tools::css('.modal.invisible', '', 'display: none !important'));
        $this->style(Tools::css('.modal', array(' > ', '.modal-overlay'), 'position: fixed',
            'top: 0', 'left: 0', 'width: 100%', 'height: 100%', 'background: var(--window-overlay)', 'z-index: 100001',
            'overflow-y: auto'));
        $this->style(Tools::css('.modal', array(' > ', '.modal-overlay', ' > ', '.modal-container'),
            'position: absolute',
            'left: 12.5%', 'top: 10%', 'width: 75%', 'overflow-y: initial',
            'background: var(--neutral)', 'z-index: 100002', 'padding: 20px',
            '-webkit-box-shadow: var(--shadow-flow-4)',
            '-moz-box-shadow: var(--shadow-flow-4)',
            'box-shadow: var(--shadow-flow-4)'));
        $this->style(Tools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container', ' > ', '.form'], 'width: 100%'));

        $this->style(Tools::css('.modal', array(' > ', '.modal-overlay', ' > ', '.closer'), 'position: absolute',
            'top: calc(10% - 10px)', 'right: calc(9.15% + 20px)', 'width: 30px', 'height: 30px',
            'z-index: 100003', 'background: var(--neutral)', 'border-radius: 45%',
            'justify-content: center', 'cursor: pointer', 'box-sizing: border-box',
            'border: 1px solid var(--system-background)'));
        $this->style(Tools::css('.modal', array(' > ', '.closer', ':hover'),
            'background: var(--system-background);'));
    }


    // Short cuts

    /**
     * Create a header.
     * @param array|string $classes A set of classes.
     * @return ContainerHeaderHtmlView
     */
    public function header($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $headerView = new ContainerHeaderHtmlView($classes);
        $this->subviews[0]->subviews[0]->add($headerView);
        return $headerView;
    }

    /**
     * Generate a closer view.
     * @param array|string $classes The classes.
     * @return CloserHtmlView
     */
    public function closer($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $closer = new CloserHtmlView($classes);
        $this->subviews[0]->add($closer);
        $closer->protocol(Event::CLICK)->
            throwevent(Event::CONTENTMODIFY, $this)->
            delete($this);
        return $closer;
    }

    /**
     * Generate a submit button view.
     * @param string $id The id.
     * @param string $formId The id of the formular.
     * @param array|string $classes The classes.
     * @return SubmitHtmlView
     */
    public function submit($id, $formId, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $submitter = new SubmitHtmlView($id, $formId, $classes);
        $this->subviews[0]->subviews[0]->add($submitter);
        return $submitter;
    }

    /**
     * Generate a abort button view.
     * @param string $id The id.
     * @param array|string $classes The classes.
     * @return AbortHtmlView
     */
    public function abort($id, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $abort = new AbortHtmlView($id, $classes);
        $this->subviews[0]->subviews[0]->add($abort);
        $abort->
            protocol(Event::CLICK)->
                throwevent(Event::CONTENTMODIFY, $this)->
                delete($this);
        return $abort;
    }

    /**
     * Adds a new Html view.
     * @param HtmlView $add The view.
     * @return HtmlView
     */
    public function item(HtmlView $add) {
        $this->subviews[0]->subviews[0]->add($add);
        return $add;
    }

    /**
     * Return a new container html view.
     * @param array|string $classes Classes.
     * @return ModalHtmlView
     */
    public static function _new($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new ModalHtmlView($classes);
    }
}
?>