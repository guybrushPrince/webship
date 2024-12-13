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
    private ?ControlPanelHtmlView $controlPanel;

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

        $this->controlPanel = ControlPanelHtmlView::createUnbounded(false);
        $this->controlPanel->fix()->reverse();
        $this->subviews[0]->subviews[0]->add($this->controlPanel);

        $this->style(Tools::css('.modal', [], '--modal-pad: 20px'));
        $this->style(Tools::css('.modal.invisible', [], 'display: none !important'));
        $this->style(Tools::css('.modal', [' > ', '.modal-overlay'], 'position: fixed',
            'top: 0', 'left: 0', 'width: 100%', 'height: 100%', 'background: var(--window-overlay)', 'z-index: 100001',
            'overflow-y: auto'));
        $this->style(Tools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container'],
            'position: absolute',
            'left: 12.5%', 'top: 10%', 'width: 75%', 'overflow-y: initial',
            'background: var(--neutral)', 'z-index: 100002', 'padding: var(--modal-pad)', 'border-radius: 5px',
            '-webkit-box-shadow: var(--shadow-flow-4)',
            '-moz-box-shadow: var(--shadow-flow-4)',
            'box-shadow: var(--shadow-flow-4)'));
        $this->style(Tools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container', ' > ', '.form'],
            'width: 100%'));
        $this->style(Tools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container', ' ', '.alert'],
            'width: 100%'));
        $this->style(Tools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container', ' > ', 'div',
            ' > ', '.ctr.fix.tr'], 'right: calc(var(--modal-pad) + var(--cell-gap))'));
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
    public function item(HtmlView $add) : HtmlView {
        $this->subviews[0]->subviews[0]->add($add);
        return $add;
    }

    /**
     * Return a new container html view.
     * @param array|string $classes Classes.
     * @return ModalHtmlView
     */
    public static function _new(string|array $classes = []) : ModalHtmlView {
        $classes = is_array($classes) ? $classes : array($classes);
        return new ModalHtmlView($classes);
    }

    /**
     * Get the control panel.
     * @return ControlPanelHtmlView|null
     */
    public function getControlPanel() : ?ControlPanelHtmlView {
        return $this->controlPanel;
    }
}
?>