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

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->add(new ContainerHtmlView(array('modal-overlay')))->ref('overlay')->
            protocol(Event::CLICK)->
                toggleClass($this, 'invisible');
        $this->add(new ContainerHtmlView(array('modal-container')));

        $this->style(Tools::css('.modal.invisible', '', 'display: none !important'));
        $this->style(Tools::css('.modal', array(' > ', '.modal-overlay'), 'position: fixed',
            'top: 0', 'left: 0', 'width: 100%', 'height: 100%', 'background: rgba(0, 0, 0, 0.5)', 'z-index: 100001'));
        $this->style(Tools::css('.modal', array(' > ', '.modal-container'), 'position: fixed',
            'left: 12.5%', 'top: 10%', 'width: 75%', 'max-height: 75%', 'overflow-y: auto',
            'background: rgba(255, 255, 255, 1.0)', 'z-index: 100002', 'padding: 20px',
            '-webkit-box-shadow: 3px 3px 10px 0px rgba(0,0,0,0.75)',
            '-moz-box-shadow: 3px 3px 10px 0px rgba(0,0,0,0.75)',
            'box-shadow: 3px 3px 10px 0px rgba(0,0,0,0.75)'));

        $this->style(Tools::css('.modal', array(' > ', '.closer'), 'position: fixed',
            'top: calc(10% - 10px)', 'right: calc(9.15% + 20px)', 'width: 30px', 'height: 30px',
            'z-index: 100003', 'background: rgba(255, 255, 255, 1.0)', 'border-radius: 45%',
            'justify-content: center', 'cursor: pointer', 'box-sizing: border-box',
            'border: 1px solid rgba(200, 200, 200, 1.0)'));
        $this->style(Tools::css('.modal', array(' > ', '.closer', ':hover'),
            'background: rgba(200, 200, 200, 1.0);'));
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
        $this->subviews[1]->add($headerView);
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
        $this->add($closer);
        $closer->
            protocol(Event::CLICK)->
                toggleClass($this, 'invisible');
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
        $this->subviews[1]->add($submitter);
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
        $this->subviews[1]->add($abort);
        $abort->
            protocol(Event::CLICK)->
                toggleClass($this, 'invisible');
        return $abort;
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
     * @param array|string $classes Classes.
     * @return ModalHtmlView
     */
    public static function _new($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new ModalHtmlView($classes);
    }
}
?>