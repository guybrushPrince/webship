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
                if ($this->event === Event::CONTENTMODIFY) $code .= '.on("' . Event::CONTENTMODIFY . '", ';
                if ($this->event === Event::OBJECTMODIFY) $code .= '.on("' . Event::OBJECTMODIFY . '", ';
                if ($this->event === Event::VARIABLE_CHANGE) $code .= '.on("' . Event::VARIABLE_CHANGE . '", ';
                if ($this->event === Event::OPEN_LIST) $code .= '.on("' . Event::OPEN_LIST . '", ';
                if ($this->event === Event::CLOSE_LIST) $code .= '.on("' . Event::CLOSE_LIST . '", ';
                if ($this->event === Event::MOVEUP) $code .= '.on("' . Event::MOVEUP . '", ';
                if ($this->event === Event::MOVEDOWN) $code .= '.on("' . Event::MOVEDOWN . '", ';
                if ($this->event === Event::MOVEDELETE) $code .= '.on("' . Event::MOVEDELETE . '", ';
                if ($this->event === Event::NEW_FILE) $code .= '.on("' . Event::NEW_FILE . '", ';
                if ($this->event === Event::DIRTY) $code .= '.on("' . Event::DIRTY . '", ';
                if ($this->event === Event::DRAGDROP) $code .= '.on("' . Event::DRAGDROP . '", ';
                if ($this->event === Event::DRAGSTART) $code .= '.on("' . Event::DRAGSTART . '", ';
                if ($this->event === Event::NEXT_STEP) $code .= '.on("' . Event::NEXT_STEP . '", ';
                if ($this->event === Event::NEXT_DEAD) $code .= '.on("' . Event::NEXT_DEAD . '", ';
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
                if ($this->delay) $code .= 'let p = lastDelay.length;' . 'let l = function() {' .
                    'if (p === lastDelay.length) {';


                if ($this->event !== Event::ONLOADCONTENT) $code .= $this->generateCode($this->children);
                if ((!array_key_exists('include children', $this->parameters) ||
                        !$this->parameters["include children"]) && $this->event !== Event::TIMED) $code .= 'sp(e);';


                if ($this->delay) $code .= '}};' . 'lastDelay.push(l);' . 'delay(lastDelay, ' . $this->delay . ');';
                if ($this->event === Event::TIMED) {
                    $code .= 'if (' . ($this->forceObject ? : '$("' . $this->parent->idc() . '").length === 0') . ') clearInterval(i);';
                }
                $code .= '})';
                if ($this->event === Event::ONLOAD) $code .= '(' . ($this->forceObject ? : '$("' . $this->parent->idc() . '")') . ')';
                if ($this->event === Event::INPUT) $code .= ')';
                if ($this->event === Event::ONKEYUP) $code .= ')';
                if ($this->event === Event::CONTENTMODIFY) $code .= ')';
                if ($this->event === Event::OBJECTMODIFY) $code .= ')';
                if ($this->event === Event::VARIABLE_CHANGE) $code .= ')';
                if ($this->event === Event::OPEN_LIST) $code .= ')';
                if ($this->event === Event::CLOSE_LIST) $code .= ')';
                if ($this->event === Event::MOVEUP) $code .= ')';
                if ($this->event === Event::MOVEDOWN) $code .= ')';
                if ($this->event === Event::NEXT_STEP) $code .= ')';
                if ($this->event === Event::NEXT_DEAD) $code .= ')';
                if ($this->event === Event::MOVEDELETE) $code .= ')';
                if ($this->event === Event::NEW_FILE) $code .= ')';
                if ($this->event === Event::DIRTY) $code .= ')';
                if ($this->event === Event::CONTEXTMENU) $code .= ')';
                if ($this->event === Event::DRAGSTART) $code .= ')';
                if ($this->event === Event::DRAGDROP) $code .= ')';
                if ($this->event === Event::TIMED) {
                    $code .= ',' . ($this->parameters['interval'] * 1000) . ')})()';
                }
                $code .= ';';
                if ($this->event === Event::ONLOADCONTENT) $code = $this->generateCode($this->children);
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
                $code .= 'if(keycode == \'13\' || event.type === "submit") {';
                $code .= $this->generateCode($this->children);
                $code .= 'return false;';
                $code .= '}';
                $code .= 'return true;';
                $code .= '});';
                if (!($v instanceof SubmitHtmlView)) {
                    $code .= '$("' . $submitView->idc() . '").click';
                    $code .= '(function(' . $submitView->id() . ') {';
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