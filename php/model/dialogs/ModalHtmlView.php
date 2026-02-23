<?php

namespace webship\dialogs;

use webship\interoperability\WEvent;
use Exception;
use webship\buttons\AbortHtmlView;
use webship\buttons\ButtonHtmlView;
use webship\buttons\CloserHtmlView;
use webship\buttons\StandardButtonHtmlView;
use webship\buttons\SubmitHtmlView;
use webship\containers\ContainerHeaderHtmlView;
use webship\containers\ContainerHtmlView;
use webship\core\HtmlView;
use webship\menus\ControlPanelHtmlView;
use webship\protocols\HtmlModifyProtocol;
use webship\WebshipTools;

/**
 * View ModalHtmlView.
 *
 * <p>A <em>modal</em> is a kind of sub window that is opened and placed over all other elements demanding
 * users to concentrate and react to the modal instead of the rest of the application. Modals are usually used to
 * request information from a user.</p>
 * <img src="http://localhost/coast/webship/modeldoc/modal.png" />
 *
 * <code>
 * $modal = ModalHtmlView::create('Hello World!', true);
 * $modal->submit('example-submit')->texts('Submit');
 * $modal->abort('example-abort')->texts('Abort');
 * $modal->item(ContainerHtmlView::createIt()->texts('Welcome to this modal!'));
 * </code>
 *
 * @package webship
 * @subpackge php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     $modal = ModalHtmlView::create('Hello World!', true);
 *     $modal->submit('example-submit')->texts('Submit');
 *     $modal->abort('example-abort')->texts('Abort');
 *     $modal->item(ContainerHtmlView::createIt()->texts('Welcome to this modal!'));
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
     * Whether the modal shall only be closed after a request.
     * @var string[]|null
     */
    private ?array $outProtected;

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     * @param string[]|null $outProtected Whether the modal should only be closed after request.
     * @param bool $invisible If it shall be invisible.
     */
    public function __construct(string|array $classes = [], ?array $outProtected = null, bool $invisible = true) {
        $classes = is_array($classes) ? $classes : [$classes];
        if ($invisible) $classes[] = 'invisible';
        parent::__construct(array_merge(['modal'], $classes));

        $this->outProtected = $outProtected;

        $overlay = $this->add(new ContainerHtmlView('modal-overlay'));
        $overlay->ref('overlay')->
        protocol(WEvent::CLICK, ['sameTarget' => true])->
        append($this->getCloseAction());
        $overlay->add(new ContainerHtmlView(['modal-container', 'gap10']));

        $this->controlPanel = ControlPanelHtmlView::createUnbounded(false);
        $this->controlPanel->fix()->reverse();
        $this->subviews[0]->subviews[0]->add($this->controlPanel);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.modal', [], '--modal-pad: 20px'));
        self::addStyle(WebshipTools::css('.modal.invisible', [], 'display: none !important'));
        self::addStyle(WebshipTools::css('.modal', [' > ', '.modal-overlay'], 'position: fixed',
            'top: 0', 'left: 0', 'width: 100%', 'height: 100%', 'background: var(--window-overlay)', 'z-index: 100001',
            'overflow-y: auto'));
        self::addStyle(WebshipTools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container'],
            'position: absolute',
            'left: 12.5%', 'top: 10%', 'width: 75%', 'overflow-y: initial',
            'background: var(--neutral)', 'z-index: 100002', 'padding: var(--modal-pad)', 'border-radius: 5px',
            '-webkit-box-shadow: var(--shadow-flow-4)',
            '-moz-box-shadow: var(--shadow-flow-4)',
            'box-shadow: var(--shadow-flow-4)'));
        self::addStyle(WebshipTools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container', ' > ', '.form'],
            'width: 100%'));
        self::addStyle(WebshipTools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container', ' ', '.alert'],
            'width: 100%'));
        self::addStyle(WebshipTools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container', ' > ', 'div',
            ' > ', '.ctr.fix.tr'], 'right: var(--cell-gap)', 'top: calc((-1)* var(--modal-pad))'));
        self::addStyle(WebshipTools::css('.modal', [' > ', '.modal-overlay', ' > ', '.modal-container',
            ' > ', 'div:not(.ctr.fix.tr)'], 'position: relative'));
    }

    /**
     * Create a modal.
     * @param string[]|string|null $header The header of the modal.
     * @param bool $withCloser If the modal shall have a closer.
     * @param string[]|null $outProtected Texts to be shown if the modal is tried to be closed.
     * @param bool $invisible If the modal shall be initialized as invisible.
     * @param string[]|string $classes Some classes.
     * @return ModalHtmlView
     */
    public static function create(array|string|null $header, bool $withCloser = true, ?array $outProtected = null,
                                  bool $invisible = false, array|string $classes = []) : ModalHtmlView {
        $view = new ModalHtmlView($classes, $outProtected, $invisible);
        if (!is_null($header)) {
            $head = $view->header();
            $head->texts($header);
        }

        if ($withCloser) $view->closer();
        return $view;
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
        $closer->protocol(WEvent::CLICK)->append($this->getCloseAction());
        return $closer;
    }

    /**
     * Create the action when the closer is pressed.
     * @return HtmlModifyProtocol
     */
    private function getCloseAction() : HtmlModifyProtocol {
        $protocol = new HtmlModifyProtocol($this);
        $current = $protocol;
        if ($this->outProtected) {
            $current = $protocol->alert($this->outProtected)->
            no()->parent()->
            yes();
        }
        $current->throwevent(WEvent::CLOSE_MODAL, $this)->delete($this);
        return $protocol;
    }

    /**
     * Generate a submit button.
     * @param string|int $id The id.
     * @param string|HtmlView|null $formId The id of the form.
     * @param string[]|string $classes Some additional classes.
     * @return SubmitHtmlView
     * @throws Exception
     */
    public function submit(string|int $id, string|HtmlView|null $formId = null,
                           string|array $classes = []) : ButtonHtmlView {
        if ($formId) $submitter = SubmitHtmlView::create($id, $classes, $formId);
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
    public function abort(string|int $id, array|string $classes = []) : AbortHtmlView {
        $classes = is_array($classes) ? $classes : [$classes];
        $abort = AbortHtmlView::create($id, $classes);
        if (!$this->control) {
            $this->control = ContainerHtmlView::createIt(['hor', 'single', 'vcent', 'hcent', 'gap10']);
            $this->subviews[0]->subviews[0]->add($this->control);
        }
        $this->control->add($abort);
        $abort->protocol(WEvent::CLICK)->
        throwevent(WEvent::CLOSE_MODAL, $this)->
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
     * @param string[]|string $classes Classes.
     * @return ModalHtmlView
     */
    public static function _new(array|string $classes = []) : ModalHtmlView {
        $classes = is_array($classes) ? $classes : [$classes];
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