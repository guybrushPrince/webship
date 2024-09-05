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
     * @var HtmlView|null
     */
    private ?HtmlView $control = null;

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
    }

    /**
     * Create a control section.
     * @return HtmlView
     */
    private function control() : HtmlView {
        if ($this->control) return $this->control;
        $this->control = new HtmlView('op-control');
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
     * @param HtmlView|string|null $image The image.
     * @return HtmlView
     */
    public function addMenuButton(HtmlView|string|null $image = null) : HtmlView {
        if ($image instanceof HtmlView) {
            $image->addClass('op-m-button');
            $button = $image;
        } else {
            $button = new HtmlView('op-m-button');
            if ($image) $button->add(new ImageHtmlView($image));
        }
        $this->control->add($button);
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

}
?>