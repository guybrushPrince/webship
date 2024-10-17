<?php

/**
 * Class ModalHtmlView.
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 */
class ModalHtmlView extends HtmlView {

    /**
     * An already created control section for buttons.
     * @var ContainerHtmlView|null
     */
    private ?ContainerHtmlView $control = null;

    /**
     * An already created control panel for small buttons.
     * @var ControlPanelHtmlView|null
     */
    private ?ControlPanelHtmlView $controlPanel = null;

    /**
     * ModalHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['modal', 'invisible'], $classes));

        $overlay = $this->add(new ContainerHtmlView('modal-overlay'));
        $overlay->ref('overlay')->
            protocol(Event::CLICK, ['sameTarget' => true])->
                delete($this);
        $overlay->add(new ContainerHtmlView(['modal-container', 'gap10']));

        $this->style(Tools::css('.modal.invisible', '', 'display: none !important'));
        $this->style(Tools::css('.modal', [' > ', '.modal-overlay'], 'position: fixed',
            'top: 0', 'left: 0', 'width: 100%', 'height: 100%', 'background: var(--window-overlay)', 'z-index: 100001',
            'overflow-y: auto'));
        $this->style(Tools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container'],
            'position: absolute',
            'left: 12.5%', 'top: 10%', 'width: 75%', 'overflow-y: initial',
            'background: var(--neutral)', 'z-index: 100002', 'padding: 20px',
            '-webkit-box-shadow: var(--shadow-flow-4)',
            '-moz-box-shadow: var(--shadow-flow-4)',
            'box-shadow: var(--shadow-flow-4)'));
        $this->style(Tools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container', ' > ', '.form'],
            'width: 100%'));
        $this->style(Tools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container', ' ', '.alert'],
            'width: 100%'));

        /*$this->style(Tools::css('.modal', [' > ', '.modal-overlay', ' > ', '.closer'],
            'position: absolute',
            'top: calc(10% - 10px)', 'right: calc(9.15% + 20px)', 'width: 30px', 'height: 30px',
            'z-index: 100003', 'background: var(--neutral)', 'border-radius: 45%',
            'justify-content: center', 'cursor: pointer', 'box-sizing: border-box',
            'border: 1px solid var(--system-background)'));
        $this->style(Tools::css('.modal', [' > ', '.closer', ':hover'],
            'background: var(--system-background);'));*/
    }

    /**
     * Create a header.
     * @param string[]|string $classes Some classes.
     * @return ContainerHeaderHtmlView
     */
    public function header(string|array $classes = []) : ContainerHeaderHtmlView {
        $headerView = new ContainerHeaderHtmlView($classes);
        $this->subviews[0]->subviews[0]->add($headerView);
        return $headerView;
    }

    /**
     * Generate a closer view.
     * @param string[]|string $classes Some classes.
     * @return CloserHtmlView
     */
    public function closer(string|array $classes = []) : CloserHtmlView {
        if (!$this->controlPanel) {
            $this->controlPanel = ControlPanelHtmlView::create(1,1, false);
            $this->controlPanel->fix();
            $this->subviews[0]->subviews[0]->add($this->controlPanel);
        }
        $closer = CloserHtmlView::_new($classes);
        $this->controlPanel->addMini($closer);
        $closer->protocol(Event::CLICK)->
            throwevent(Event::CONTENTMODIFY, $this)->
            delete($this);
        return $closer;
    }

    /**
     * Generate a submit button.
     * @param string|int $id The id.
     * @param string|HtmlView|null $formId The id of the form.
     * @param string[]|string $classes Some additional classes.
     * @return SubmitHtmlView
     */
    public function submit(string|int $id, string|HtmlView|null $formId = null,
                           string|array $classes = []) : ButtonHtmlView {
        if ($formId) $submitter = new SubmitHtmlView($id, $formId, $classes);
        else $submitter = StandardButtonHtmlView::create($id, $classes);
        if (!$this->control) {
            $this->control = new ContainerHtmlView(['hor', 'single', 'vcent', 'hcent', 'gap10']);
            $this->subviews[0]->subviews[0]->add($this->control);
        }
        $this->control->add($submitter);
        return $submitter;
    }

    /**
     * Generate a abort button .
     * @param string|int $id The id.
     * @param string[]|string $classes Some additional classes.
     * @return AbortHtmlView
     */
    public function abort(string|int $id, string|array $classes = []) : AbortHtmlView {
        $classes = is_array($classes) ? $classes : array($classes);
        $abort = new AbortHtmlView($id, $classes);
        if (!$this->control) {
            $this->control = new ContainerHtmlView(['hor', 'single', 'vcent', 'hcent', 'gap10']);
            $this->subviews[0]->subviews[0]->add($this->control);
        }
        $this->control->add($abort);
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