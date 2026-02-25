<?php

namespace webship\protocols;

use ship\protocols\EventProtocol;
use ship\protocols\Protocol;
use ship\views\DirectView;
use webship\buttons\SubmitHtmlView;
use webship\core\HtmlView;
use webship\interoperability\WEvent;

/**
 * Class HtmlEventProtocol
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlEventProtocol extends EventProtocol {

    /**
     * Additional events being registered.
     * @var array
     */
    private static array $REGISTERED_EVENTS = [];

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
     * Register additional events.
     * @param array|string[]|int[]|string|int $events Additional events.
     * @return void
     */
    public static function registerEvents(array|string|int $events) : void {
        if (!is_array($events)) $events = [ $events ];
        self::$REGISTERED_EVENTS = array_merge(self::$REGISTERED_EVENTS, $events);
    }

    /**
     * @inheritDoc
     */
    public function generate() : string {
        $code = '';
        if ($this->parent instanceof HtmlView) {
            $fn = ($this->attachEventPermanently ? '._on' : '.on');

            if (!($this->parent instanceof SubmitHtmlView && $this->event === WEvent::CLICK)) {
                if (!$this->forceObject) $code .= '$("' . $this->parent->idc() . '")';
                else $code .= $this->forceObject;
                if ($this->event === WEvent::CLICK) $code .= $fn . '("click", ';
                else if ($this->event === WEvent::CHANGE) $code .= $fn . '("change", ';
                else if ($this->event === WEvent::ONMOUSEOVER) $code .= $fn . '("mouseover", ';
                else if ($this->event === WEvent::ONMOUSEOUT) $code .= $fn . '("mouseout", ';
                else if ($this->event === WEvent::ONFOCUSOUT) $code .= $fn . '("focusout", ';
                else if ($this->event === WEvent::ONFOCUSIN) $code .= $fn . '("focusin", ';
                else if ($this->event === WEvent::ONKEYUP) $code .= $fn . '("keyup", ';
                else if ($this->event === WEvent::INPUT) $code .= $fn . '("input", ';
                else if (in_array($this->event, self::$REGISTERED_EVENTS)) {
                    $code .= $fn . '("' . $this->event . '", ';
                } else {
                    switch ($this->event) {
                        case WEvent::CONTENTMODIFY:
                        case WEvent::OBJECTMODIFY:
                        case WEvent::VARIABLE_CHANGE:
                        case WEvent::OPEN_LIST:
                        case WEvent::CLOSE_LIST:
                        case WEvent::MOVEUP:
                        case WEvent::MOVEDOWN:
                        case WEvent::MOVEDELETE:
                        case WEvent::NEW_FILE:
                        case WEvent::DIRTY:
                        case WEvent::DRAGDROP:
                        case WEvent::DRAGSTART:
                        case WEvent::NEXT_STEP:
                        case WEvent::NEXT_DEAD:
                        case WEvent::ON_IMPORT:
                        case WEvent::CLOSE_MODAL:
                            $code .= $fn . '("' . $this->event . '", ';
                    }
                }
                if ($this->event === WEvent::CONTEXTMENU) $code .= $fn . '("contextmenu", ';
                if ($this->event === WEvent::CLICK_OUTSIDE) $code = '$(window)' . $fn . '("click", ';
                if ($this->event === WEvent::TIMED) {
                    $code = '(() => { let i = setInterval(';
                }
                if ($this->event === WEvent::DELAYED) {
                    $code = '(() => { let i = setTimeout(';
                }
                if ($this->event === WEvent::ONLOAD) $code = '';
                $code .= '(function(e' . ($this->event === WEvent::CONTENTMODIFY || $this->event === WEvent::OBJECTMODIFY ||
                    $this->event === WEvent::DIRTY || $this->event === WEvent::DRAGDROP || $this->event === WEvent::DRAGSTART ? ',eData={}' : '') . ') {';
                if ($this->sameTarget || $this->event === WEvent::OBJECTMODIFY)
                    $code .= 'if (e.target !== this) {sp(e); e.preventDefault(); return;}';
                if ($this->event === WEvent::CONTEXTMENU) {
                    $code .= 'e.preventDefault();';
                }
                if ($this->event === WEvent::ONFOCUSOUT) {
                    $code .= 'if (this.contains(e.relatedTarget)) return;';
                }
                $code .= 'let _i_n_ = "anonymous";';
                if ($this->delay) $code .= 'let p = lastDelay.length;' . 'let l = function() {' .
                    'if (p === lastDelay.length) {';


                if ($this->event !== WEvent::ONLOADCONTENT) $code .= $this->generateCode($this->children);
                if ((!array_key_exists('include children', $this->parameters) ||
                        !$this->parameters["include children"]) &&
                    !in_array($this->event, [WEvent::TIMED, WEvent::DELAYED])) $code .= 'sp(e);';


                if ($this->delay) $code .= '}};' . 'lastDelay.push(l);' . 'delay(lastDelay, ' . $this->delay . ');';
                if ($this->event === WEvent::TIMED) {
                    $code .= 'if (' . ($this->forceObject ?: '$("[data-id=' . $this->parent->id() . '").length === 0') . ') {' .
                        'clearInterval(i);' .
                        'delete context.timers[i];' .
                        '}';
                }
                if ($this->event === WEvent::DELAYED) {
                    $code .= 'if (' . ($this->forceObject ?: '$("[data-id=' . $this->parent->id() . '").length === 0') . ') {' .
                        'clearTimeout(i);' .
                        'delete context.timers[i];' .
                        '}';
                }
                $code .= '})';
                if ($this->event === WEvent::ONLOAD) $code .= '(' . ($this->forceObject ?: '$("' . $this->parent->idc() . '")') . ')';
                switch ($this->event) {
                    case WEvent::CLICK:
                    case WEvent::CLICK_OUTSIDE:
                    case WEvent::CHANGE:
                    case WEvent::ONMOUSEOVER:
                    case WEvent::ONMOUSEOUT:
                    case WEvent::ONFOCUSOUT:
                    case WEvent::ONFOCUSIN:
                    case WEvent::INPUT:
                    case WEvent::ONKEYUP:
                    case WEvent::CONTENTMODIFY:
                    case WEvent::OBJECTMODIFY:
                    case WEvent::VARIABLE_CHANGE:
                    case WEvent::OPEN_LIST:
                    case WEvent::CLOSE_LIST:
                    case WEvent::MOVEUP:
                    case WEvent::MOVEDOWN:
                    case WEvent::NEXT_STEP:
                    case WEvent::NEXT_DEAD:
                    case WEvent::MOVEDELETE:
                    case WEvent::NEW_FILE:
                    case WEvent::DIRTY:
                    case WEvent::CONTEXTMENU:
                    case WEvent::DRAGSTART:
                    case WEvent::DRAGDROP:
                    case WEvent::ON_IMPORT:
                    case WEvent::CLOSE_MODAL: {
                        $code .= ')';
                        break;
                    }
                    default: {
                        if (in_array($this->event, self::$REGISTERED_EVENTS)) {
                            $code .= ')';
                        }
                    }
                }
                if (in_array($this->event, [WEvent::TIMED, WEvent::DELAYED])) {
                    $code .= ',' . ($this->parameters['interval'] * 1000) . '); context.timers[i] = i;})();';
                }
                $code .= ';';
                if ($this->event === WEvent::ONLOADCONTENT) {
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