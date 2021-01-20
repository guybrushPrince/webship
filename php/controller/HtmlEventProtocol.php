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
     * @inheritDoc
     */
    public function __construct($event, DirectView $parent, $delay = false) {
        parent::__construct($event, $parent, "HtmlModifyProtocol");
        $this->delay = $delay;
    }

    /**
     * @inheritDoc
     */
    public function generate() {
        $code = '';
        if ($this->parent instanceof HtmlView) {
            if (!($this->parent instanceof SubmitHtmlView && $this->event === Event::CLICK)) {
                $code .= '$("' . $this->parent->idc() . '")';
                if ($this->event === Event::CLICK) $code .= '.click';
                if ($this->event === Event::CHANGE) $code .= '.change';
                if ($this->event === Event::ONMOUSEOVER) $code .= '.mouseover';
                if ($this->event === Event::ONMOUSEOUT) $code .= '.mouseout';
                if ($this->event === Event::ONFOCUSOUT) $code .= '.focusout';
                if ($this->event === Event::ONFOCUSIN) $code .= '.focusin';
                if ($this->event === Event::ONKEYUP) $code .= '.keyup';
                if ($this->event === Event::INPUT) $code .= '.on("input", ';
                if ($this->event === Event::CONTENTMODIFY) $code .= '.on("' . Event::CONTENTMODIFY . '", ';
                if ($this->event === Event::MOVEUP) $code .= '.on("' . Event::MOVEUP . '", ';
                if ($this->event === Event::MOVEDOWN) $code .= '.on("' . Event::MOVEDOWN . '", ';
                if ($this->event === Event::MOVEDELETE) $code .= '.on("' . Event::MOVEDELETE . '", ';
                if ($this->event === Event::CONTEXTMENU) $code .= '.on("contextmenu", ';
                if ($this->event === Event::CLICK_OUTSIDE) $code = '$(window).click';
                if ($this->event === Event::ONLOAD) $code = '';
                $code .= '(function(e) {';
                if ($this->event === Event::CONTEXTMENU) {
                    $code .= 'e.preventDefault();';
                }
                if ($this->event === Event::ONFOCUSOUT) {
                    $code .= 'if (this.contains(e.relatedTarget)) return;';
                }
                if ($this->delay) $code .= 'let p = lastDelay.length;' . 'let l = function() {' .
                    'if (p === lastDelay.length) { console.log(p); console.log(lastDelay.length);';
                if ($this->event !== Event::ONLOADCONTENT) $code .= $this->generateCode($this->children);
                $code .= 'sp(e);';
                if ($this->delay) $code .= '}};' . 'lastDelay.push(l);' . 'delay(lastDelay, ' . $this->delay . ');';
                $code .= '})';
                if ($this->event === Event::ONLOAD) $code .= '($("' . $this->parent->idc() . '"))';
                if ($this->event === Event::INPUT) $code .= ')';
                if ($this->event === Event::CONTENTMODIFY) $code .= ')';
                if ($this->event === Event::MOVEUP) $code .= ')';
                if ($this->event === Event::MOVEDOWN) $code .= ')';
                if ($this->event === Event::MOVEDELETE) $code .= ')';
                if ($this->event === Event::CONTEXTMENU) $code .= ')';
                $code .= ';';
                if ($this->event === Event::ONLOADCONTENT) $code = $this->generateCode($this->children);
            } else {
                $submitView = $this->parent;
                $v = $submitView->submitForm();
                $method = 'keypress';
                if (!$v) {
                    $method = 'click';
                    $v = $submitView;
                }
                $code .= '$("' . $v->idc() . '").' . $method;
                $code .= '(function(' . $v->id() . ') {';
                $code .= 'var keycode = (event.keyCode ? event.keyCode : event.which);';
                $code .= 'if(keycode == \'13\') {';
                $code .= $this->generateCode($this->children);
                $code .= '}';
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