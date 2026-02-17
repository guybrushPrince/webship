<?php

namespace webship\protocols;

use Event;
use ship\protocols\EventProtocol;
use ship\protocols\Protocol;
use ship\views\DirectView;
use webship\buttons\SubmitHtmlView;
use webship\core\HtmlView;

/**
 * Class HtmlEventProtocol
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlEventProtocol extends EventProtocol {

    /**
     * Whether the event should be handled with a delay, or not.
     * @var int|bool
     */
    private int|bool $delay = false;

    /**
     * Whether the event should only be handled by the specified target, or not.
     * @var bool
     */
    private bool $sameTarget = false;

    /**
     * Whether the current event shall be attached permanently, or not.
     * @var bool
     */
    private bool $attachEventPermanently = false;

    /**
     * Additional parameters.
     * @var array
     */
    private array $parameters;

    /**
     * Whether the object shall be forced to be taken instead of the parent.
     * @var string|null
     */
    private ?string $forceObject;

    /**
     * @inheritDoc
     */
    public function __construct(int $event, DirectView $parent, array $params = [], ?string $forceObject = null) {
        parent::__construct($event, $parent, HtmlModifyProtocol::class);
        $this->delay = (array_key_exists('delay', $params) ? $params['delay'] : false);
        $this->sameTarget = (array_key_exists('sameTarget', $params) ? $params['sameTarget'] : false);
        $this->attachEventPermanently = (array_key_exists('attach', $params) ? $params['attach'] : false);
        $this->parameters = $params;
        $this->forceObject = $forceObject;
    }

    /**
     * @inheritDoc
     */
    public function generate() : string {
        $code = '';
        if ($this->parent instanceof HtmlView) {
            $fn = ($this->attachEventPermanently ? '._on' : '.on');

            if (!($this->parent instanceof SubmitHtmlView && $this->event === Event::CLICK)) {
                if (!$this->forceObject) $code .= '$("' . $this->parent->idc() . '")';
                else $code .= $this->forceObject;
                if ($this->event === Event::CLICK) $code .= $fn . '("click", ';
                if ($this->event === Event::CHANGE) $code .= $fn . '("change", ';
                if ($this->event === Event::ONMOUSEOVER) $code .= $fn . '("mouseover", ';
                if ($this->event === Event::ONMOUSEOUT) $code .= $fn . '("mouseout", ';
                if ($this->event === Event::ONFOCUSOUT) $code .= $fn . '("focusout", ';
                if ($this->event === Event::ONFOCUSIN) $code .= $fn . '("focusin", ';
                if ($this->event === Event::ONKEYUP) $code .= $fn . '("keyup", ';
                if ($this->event === Event::INPUT) $code .= $fn . '("input", ';
                switch ($this->event) {
                    case Event::CONTENTMODIFY:
                    case Event::OBJECTMODIFY:
                    case Event::VARIABLE_CHANGE:
                    case Event::OPEN_LIST:
                    case Event::CLOSE_LIST:
                    case Event::MOVEUP:
                    case Event::MOVEDOWN:
                    case Event::MOVEDELETE:
                    case Event::NEW_FILE:
                    case Event::DIRTY:
                    case Event::DRAGDROP:
                    case Event::DRAGSTART:
                    case Event::NEXT_STEP:
                    case Event::NEXT_DEAD:
                    case Event::JOB_START:
                    case Event::JOB_IN_PROGRESS:
                    case Event::JOB_DONE:
                    case Event::JOB_ERROR:
                    case Event::ON_IMPORT:
                    case Event::CLOSE_MODAL:
                        $code .= $fn . '("' . $this->event . '", ';
                }
                if ($this->event === Event::CONTEXTMENU) $code .= $fn . '("contextmenu", ';
                if ($this->event === Event::CLICK_OUTSIDE) $code = '$(window)' . $fn . '("click", ';
                if ($this->event === Event::TIMED) {
                    $code = '(() => { let i = setInterval(';
                }
                if ($this->event === Event::DELAYED) {
                    $code = '(() => { let i = setTimeout(';
                }
                if ($this->event === Event::ONLOAD) $code = '';
                $code .= '(function(e' . ($this->event === Event::CONTENTMODIFY || $this->event === Event::OBJECTMODIFY ||
                    $this->event === Event::DIRTY || $this->event === Event::DRAGDROP || $this->event === Event::DRAGSTART ? ',eData={}' : '') . ') {';
                if ($this->sameTarget || $this->event === Event::OBJECTMODIFY)
                    $code .= 'if (e.target !== this) {sp(e); e.preventDefault(); return;}';
                if ($this->event === Event::CONTEXTMENU) {
                    $code .= 'e.preventDefault();';
                }
                if ($this->event === Event::ONFOCUSOUT) {
                    $code .= 'if (this.contains(e.relatedTarget)) return;';
                }
                $code .= 'let _i_n_ = "anonymous";';
                if ($this->delay) $code .= 'let p = lastDelay.length;' . 'let l = function() {' .
                    'if (p === lastDelay.length) {';


                if ($this->event !== Event::ONLOADCONTENT) $code .= $this->generateCode($this->children);
                if ((!array_key_exists('include children', $this->parameters) ||
                        !$this->parameters["include children"]) &&
                    !in_array($this->event, [Event::TIMED, Event::DELAYED])) $code .= 'sp(e);';


                if ($this->delay) $code .= '}};' . 'lastDelay.push(l);' . 'delay(lastDelay, ' . $this->delay . ');';
                if ($this->event === Event::TIMED) {
                    $code .= 'if (' . ($this->forceObject ?: '$("[data-id=' . $this->parent->id() . '").length === 0') . ') {' .
                        'clearInterval(i);' .
                        'delete context.timers[i];' .
                        '}';
                }
                if ($this->event === Event::DELAYED) {
                    $code .= 'if (' . ($this->forceObject ?: '$("[data-id=' . $this->parent->id() . '").length === 0') . ') {' .
                        'clearTimeout(i);' .
                        'delete context.timers[i];' .
                        '}';
                }
                $code .= '})';
                if ($this->event === Event::ONLOAD) $code .= '(' . ($this->forceObject ?: '$("' . $this->parent->idc() . '")') . ')';
                switch ($this->event) {
                    case Event::CLICK:
                    case Event::CLICK_OUTSIDE:
                    case Event::CHANGE:
                    case Event::ONMOUSEOVER:
                    case Event::ONMOUSEOUT:
                    case Event::ONFOCUSOUT:
                    case Event::ONFOCUSIN:
                    case Event::INPUT:
                    case Event::ONKEYUP:
                    case Event::CONTENTMODIFY:
                    case Event::OBJECTMODIFY:
                    case Event::VARIABLE_CHANGE:
                    case Event::OPEN_LIST:
                    case Event::CLOSE_LIST:
                    case Event::MOVEUP:
                    case Event::MOVEDOWN:
                    case Event::NEXT_STEP:
                    case Event::NEXT_DEAD:
                    case Event::MOVEDELETE:
                    case Event::NEW_FILE:
                    case Event::DIRTY:
                    case Event::CONTEXTMENU:
                    case Event::DRAGSTART:
                    case Event::DRAGDROP:
                    case Event::JOB_START:
                    case Event::JOB_IN_PROGRESS:
                    case Event::JOB_DONE:
                    case Event::JOB_ERROR:
                    case Event::ON_IMPORT:
                    case Event::CLOSE_MODAL:
                        $code .= ')';
                }
                if (in_array($this->event, [Event::TIMED, Event::DELAYED])) {
                    $code .= ',' . ($this->parameters['interval'] * 1000) . '); context.timers[i] = i;})();';
                }
                $code .= ';';
                if ($this->event === Event::ONLOADCONTENT) {
                    $code = '(function() {';
                    $code .= 'let _i_n_ = "anonymous";';
                    $code .= $this->generateCode($this->children);
                    $code .= '})();';
                }
            } else {
                $submitView = $this->parent;
                $v = $submitView->submitForm();
                if ($submitView instanceof SubmitHtmlView) $method = 'submit keypress';
                else $method = 'keypress';
                if (!$v) {
                    $method = 'click';
                    $v = $submitView;
                }
                $code .= '$("' . $v->idc() . '")' . $fn . '("' . $method . '", ';
                $code .= 'function(' . $v->id() . ') {';
                $code .= 'var keycode = (event.keyCode ? event.keyCode : event.which);';
                $code .= 'if(keycode == \'13\' || event.type === "submit" || event.type === "click") {';
                $code .= 'let _i_n_ = "anonymous";';
                $code .= $this->generateCode($this->children);
                $code .= 'return false;';
                $code .= '}';
                $code .= 'return true;';
                $code .= '});';
                if (!($v instanceof SubmitHtmlView)) {
                    $code .= '$("' . $submitView->idc() . '")' . $fn . '("click", ';
                    $code .= '(function(' . $submitView->id() . ') {';
                    $code .= 'let _i_n_ = "anonymous";';
                    $code .= $this->generateCode($this->children);
                    $code .= '}));';
                }

            }
        }
        return $code;
    }

    private function generateCode(array $protocols) {
        return implode(array_map(function (Protocol $p) {
            return $p->generate();
        }, $protocols));
    }
}
?>