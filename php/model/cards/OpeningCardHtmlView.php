<?php

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
     * @var null
     */
    private $object = null;

    /**
     * Whether the card is opened, or not.
     * @var bool|null
     */
    private ?bool $opened = null;

    /**
     * The title view.
     * @var HtmlView
     */
    private HtmlView $titleView;

    /**
     * Constructor.
     * @param bool|null $opened Whether the card is opened, or not.
     * @param string|string[] $classes Some additional classes.
     */
    public function __construct(?bool $opened = false, string|array $classes = []) {
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

        $titleView->protocol(Event::CLICK)->
            doIt('ToggleClass', ['view' => $this, 'class' => 'wide'], 'basics')->
            doIt('ToggleClass', ['view' => $contentView, 'class' => 'invisible'], 'basics');

        $this->opened = $opened;
        $this->titleView = $titleView;
    }

    /**
     * Binds an object for opening the card.
     * @param PersistentTrait $object The object.
     * @param HtmlModifyProtocol|string|null $protocol The function to execute.
     * @return void
     */
    public function bindOpening($object, HtmlModifyProtocol|string|null $protocol = null) : void {
        $this->bind($object, Event::CONTENTMODIFY, $protocol, WebshipScopes::OPENING_CARD_OPENS);
        $this->object = $object;
    }

    /**
     * Binds an object for opening the card.
     * @param PersistentTrait $object The object.
     * @param HtmlModifyProtocol|string|null $protocol The function to execute.
     * @return void
     */
    public function bindClosing($object, HtmlModifyProtocol|string|null $protocol = null) : void {
        $this->bind($object, Event::CONTENTMODIFY, $protocol, WebshipScopes::OPENING_CARD_CLOSES);
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
    public function generateOpeningTag() {
        if ($this->object) {
            if (!$this->opened) {
                $this->titleView->protocol(Event::CLICK)->
                    doIt('ThrowEventToObject', ['object' => $this->object, 'event' => Event::CONTENTMODIFY, 'scope' => WebshipScopes::OPENING_CARD_OPENS], 'basics')->
                    doIt('UnbindEventFromObject', ['view' => $this, 'object' => $this->object, 'event' => Event::CONTENTMODIFY, 'scope' => WebshipScopes::OPENING_CARD_OPENS], 'basics');
            } else {
                $this->titleView->protocol(Event::CLICK)->
                    doIt('ThrowEventToObject', ['object' => $this->object, 'event' => Event::CONTENTMODIFY, 'scope' => WebshipScopes::OPENING_CARD_CLOSES], 'basics')->
                    doIt('UnbindEventFromObject', ['view' => $this, 'object' => $this->object, 'event' => Event::CONTENTMODIFY, 'scope' => WebshipScopes::OPENING_CARD_OPENS], 'basics');
            }
        }
        return parent::generateOpeningTag();
    }


}
?>