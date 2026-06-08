<?php

namespace webship\cards;

use ship\interoperability\IShippable;
use webship\controller\BasicEvents;
use webship\controller\WebshipScopes;
use webship\core\HtmlView;
use webship\interoperability\WEvent;
use webship\menus\ControlPanelHtmlView;
use webship\protocols\HtmlModifyProtocol;

/**
 * Class OpeningCardHtmlView.
 *
 * @package webship
 * @subpackge php/model/cards
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class OpeningCardHtmlView extends CardHtmlView {

    /**
     * The control view.
     * @var ControlPanelHtmlView|null
     */
    private ?ControlPanelHtmlView $control = null;

    /**
     * The id view.
     * @var HtmlView|null
     */
    private ?HtmlView $id;

    /**
     * The title text view.
     * @var HtmlView|null
     */
    private ?HtmlView $titleField;

    /**
     * A bounded object.
     * @var IShippable|null
     */
    private IShippable|null $object = null;

    /**
     * Whether the card is opened, or not.
     * @var bool|null
     */
    private ?bool $opened = null;

    /**
     * Whether the card can be opened, or not.
     * @var bool
     */
    private bool $openable = true;

    /**
     * The title view.
     * @var HtmlView
     */
    private HtmlView $titleView;

    /**
     * Constructor.
     * @param bool|null $opened Whether the card is opened, or not.
     * @param bool $openable Whether the card can be opened, or not.
     * @param string|string[] $classes Some additional classes.
     */
    public function __construct(bool|null $opened = false, bool $openable = true, array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'op-card';
        parent::__construct($classes);

        $this->ref('op-card');

        $titleView = $this->title();
        $this->titleField = new HtmlView();
        $titleView->add($this->titleField);
        $this->id = new HtmlView('id');
        $titleView->add($this->id);

        $contentView = $this->content(!$opened ? 'invisible' : []);
        $contentView->ref('card-content');

        $this->control();

        if ($openable) {
            $titleView->protocol(WEvent::CLICK)->
                doIt(BasicEvents::ToggleClass, ['view' => $this, 'class' => 'wide'], BasicEvents::environment())->
                doIt(BasicEvents::ToggleClass, ['view' => $contentView, 'class' => 'invisible'], BasicEvents::environment());
        }

        $this->opened = $opened;
        $this->titleView = $titleView;
    }

    /**
     * Binds an object for opening the card.
     * @param IShippable $object The object.
     * @param HtmlModifyProtocol|string|null $protocol The function to execute.
     * @return void
     */
    public function bindOpening(IShippable $object, HtmlModifyProtocol|string|null $protocol = null) : void {
        $this->bind($object, WEvent::CONTENTMODIFY, $protocol, WebshipScopes::OPENING_CARD_OPENS);
        $this->object = $object;
    }

    /**
     * Binds an object for opening the card.
     * @param IShippable $object The object.
     * @param HtmlModifyProtocol|string|null $protocol The function to execute.
     * @return void
     */
    public function bindClosing(IShippable $object, HtmlModifyProtocol|string|null $protocol = null) : void {
        $this->bind($object, WEvent::CONTENTMODIFY, $protocol, WebshipScopes::OPENING_CARD_CLOSES);
        $this->object = $object;
    }

    /**
     * Create a control section.
     * @return ControlPanelHtmlView
     */
    public function control() : ControlPanelHtmlView {
        if ($this->control) return $this->control;
        $this->control = ControlPanelHtmlView::createUnbounded(false)->fix()->reverse();
        $this->content()->add($this->control);
        return $this->control;
    }

    /**
     * Adds an id.
     * @param string $id The id.
     * @return void
     */
    public function addId(string $id) : void {
        $this->id->texts()->text(self::getLanguage(), $id);
    }

    /**
     * Adds a menu button to the control section.
     * @param HtmlView|null $button The button.
     * @return HtmlView
     */
    public function addMenuButton(HtmlView|null $button = null) : HtmlView {
        $this->control()->addMini($button);
        return $button;
    }

    /**
     * Sets the title text.
     * @param string $title The title.
     * @param string|null $acronym An optional acronym.
     * @return void
     */
    public function setTitle(string $title, ?string $acronym = null) : void {
        if (!$acronym) $acronym = '<span></span>';
        $this->titleField->texts()->text(self::getLanguage(), $acronym . $title);
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        if ($this->object && $this->openable) {
            if (!$this->opened) {
                $this->titleView->protocol(WEvent::CLICK)->
                    doIt(BasicEvents::ThrowEventToObject, ['object' => $this->object, 'event' => WEvent::CONTENTMODIFY, 'scope' => WebshipScopes::OPENING_CARD_OPENS], BasicEvents::environment())->
                    doIt(BasicEvents::UnbindEventFromObject, ['view' => $this, 'object' => $this->object, 'event' => WEvent::CONTENTMODIFY, 'scope' => WebshipScopes::OPENING_CARD_OPENS], BasicEvents::environment());
            } else {
                $this->titleView->protocol(WEvent::CLICK)->
                    doIt(BasicEvents::ThrowEventToObject, ['object' => $this->object, 'event' => WEvent::CONTENTMODIFY, 'scope' => WebshipScopes::OPENING_CARD_CLOSES], BasicEvents::environment())->
                    doIt(BasicEvents::UnbindEventFromObject, ['view' => $this, 'object' => $this->object, 'event' => WEvent::CONTENTMODIFY, 'scope' => WebshipScopes::OPENING_CARD_OPENS], BasicEvents::environment());
            }
        }
        return parent::generateOpeningTag();
    }
}
?>