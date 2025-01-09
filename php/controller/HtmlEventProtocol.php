<?php

include_once dirname(__FILE__) . '/../../../ship/php/EventProtocol.php';

/**
 * Class HtmlEventProtocol
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlEventProtocol extends EventProtocol {

    /**
     * Whether the event should be handled with a delay.
     * @var integer|boolean
     */
    private $delay = false;

    /**
     * Whether the event should only be handled by the specified target.
     * @var bool
     */
    private $sameTarget = false;

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
        parent::__construct($event, $parent, "HtmlModifyProtocol");
        $this->delay = (array_key_exists('delay', $params) ? $params['delay'] : false);
        $this->sameTarget = (array_key_exists('sameTarget', $params) ? $params['sameTarget'] : false);
        $this->parameters = $params;
        $this->forceObject = $forceObject;
    }

    /**
     * @inheritDoc
     */
    public function generate() : string {
        $code = '';
        if ($this->parent instanceof HtmlView) {
            if (!($this->parent instanceof SubmitHtmlView && $this->event === Event::CLICK)) {
                if (!$this->forceObject) $code .= '$("' . $this->parent->idc() . '")';
                else $code .= $this->forceObject;
                if ($this->event === Event::CLICK) $code .= '.click';
                if ($this->event === Event::CHANGE) $code .= '.change';
                if ($this->event === Event::ONMOUSEOVER) $code .= '.mouseover';
                if ($this->event === Event::ONMOUSEOUT) $code .= '.mouseout';
                if ($this->event === Event::ONFOCUSOUT) $code .= '.focusout';
                if ($this->event === Event::ONFOCUSIN) $code .= '.focusin';
                if ($this->event === Event::ONKEYUP) $code .= '.on("keyup", ';
                if ($this->event === Event::INPUT) $code .= '.on("input", ';
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
                    case Event::CLOSE_MODAL: $code .= '.on("' . $this->event . '", ';
                }
                if ($this->event === Event::CONTEXTMENU) $code .= '.on("contextmenu", ';
                if ($this->event === Event::CLICK_OUTSIDE) $code = '$(window).click';
                if ($this->event === Event::TIMED) {
                    $code = '(() => { let i = setInterval(';
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
                        !$this->parameters["include children"]) && $this->event !== Event::TIMED) $code .= 'sp(e);';


                if ($this->delay) $code .= '}};' . 'lastDelay.push(l);' . 'delay(lastDelay, ' . $this->delay . ');';
                if ($this->event === Event::TIMED) {
                    $code .= 'if (' . ($this->forceObject ? : '$("[data-id=' . $this->parent->id() . '").length === 0') . ') clearInterval(i);';
                }
                $code .= '})';
                if ($this->event === Event::ONLOAD) $code .= '(' . ($this->forceObject ? : '$("' . $this->parent->idc() . '")') . ')';
                switch ($this->event) {
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
                    case Event::CLOSE_MODAL: $code .= ')';
                }
                if ($this->event === Event::TIMED) {
                    $code .= ',' . ($this->parameters['interval'] * 1000) . ')})()';
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
                $code .= '$("' . $v->idc() . '").on("' . $method . '", ';
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
                    $code .= '$("' . $submitView->idc() . '").click';
                    $code .= '(function(' . $submitView->id() . ') {';
                    $code .= 'let _i_n_ = "anonymous";';
                    $code .= $this->generateCode($this->children);
                    $code .= '});';
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