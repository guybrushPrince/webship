<?php

namespace webship\cards;

use webship\core\HtmlView;
use webship\inputs\EditableHtmlView;
use webship\WebshipTools;

/**
 * View CardHtmlView.
 *
 * <p>A card is illustrated similarly to a polaroid photography. It usually has a title part and a content part.</p>
 *
 * <code>
 * $card = CardHtmlView::create('Hello World!');
 * $card->content()->texts('You are beautiful!');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/card.png" />
 *
 * @title Card
 * @category cards
 * 
 * @package webship
 * @subpackage php/model/cards
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.3.0
 *
 * @view
 * @examples
 *     $card = CardHtmlView::create('Hello World!');
 *     $card->content()->texts('You are beautiful!');
 */
class CardHtmlView extends HtmlView {

    /**
     * The title view.
     * @var HtmlView|null
     */
    private ?HtmlView $title = null;

    /**
     * The content view.
     * @var HtmlView|null
     */
    private ?HtmlView $content = null;

    /**
     * CardHtmlView constructor.
     * @param string[]|string $classes Some additional classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge($classes, ['card']));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.card', [], 'position: relative', 'margin: .5rem 0 1rem 0',
            'background-color: var(--ws-card-bg)', '-webkit-transition: -webkit-box-shadow .25s',
            'transition: box-shadow .25s', 'border-radius: 2px',
            '-webkit-box-shadow: var(--ws-haptic-shadow)',
            'box-shadow: var(--ws-haptic-shadow)'));
        self::addStyle(WebshipTools::css('.card', [':hover'],
            '-webkit-box-shadow: var(--ws-haptic-fc-shadow)',
            'box-shadow: var(--ws-haptic-fc-shadow)'));

        self::addStyle(WebshipTools::css('.card.disabled', '', 'opacity: 0.5'));
        self::addStyle(WebshipTools::css('.card.disabled', [':hover'],
            '-webkit-box-shadow: var(--ws-haptic-shadow)',
            'box-shadow: var(--ws-haptic-shadow)'));

        self::addStyle(WebshipTools::css('.card', [' > ', '.title'], 'color: var(--ws-main-font)',
            'width: 100%', 'max-width: 100%', 'padding: 24px', 'font-size: 24px',
            'font-weight: 300', 'box-sizing: border-box', 'background: var(--ws-card-title-bg)',
            'border-bottom: solid 1px var(--ws-card-title-brd)'));
        self::addStyle(WebshipTools::css('.card', [' > ', '.content'], 'padding: 5px'));

        self::addStyle(WebshipTools::css('.card.minimal', [' > ', '.title'], 'padding: 3px', 'font-size: 14px',
            'text-transform: uppercase'));
        self::addStyle(WebshipTools::css('.card.minimal', [' > ', '.content'], 'font-size: 9px'));
        self::addStyle(WebshipTools::css('.card.pointer', [], 'cursor: pointer'));
    }

    /**
     * Create a card.
     * @param string[]|string|null $title Optional title.
     * @param string[]|string $classes Some classes.
     * @param bool $editableTitle If the title shall be editable, or not.
     * @return CardHtmlView
     */
    public static function create(array|string|null $title = null, array|string $classes = [],
                                  bool              $editableTitle = false) : CardHtmlView {
        $card = new CardHtmlView($classes);
        if (!is_null($title)) {
            $card->title($editableTitle)->texts($title);
        }
        return $card;
    }

    /**
     * Creates a title.
     * @param bool $editable If the title should be editable.
     * @param string|string[] $classes Some classes.
     * @return HtmlView
     */
    public function title(bool $editable = false, string|array $classes = []) : HtmlView {
        if ($this->title) return $this->title;
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'title';
        $titleView = (!$editable ? new HtmlView($classes) : EditableHtmlView::create($classes));
        $this->add($titleView);
        $this->title = $titleView;
        return $titleView;
    }

    /**
     * Creates a content view.
     * @param string|string[] $classes Some classes.
     * @return HtmlView
     */
    public function content(array|string $classes = []) : HtmlView {
        if ($this->content) return $this->content;
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'content';
        $contentView = new HtmlView($classes);
        $this->add($contentView);
        $this->content = $contentView;
        return $contentView;
    }

    /**
     * Make the card minimal.
     * @return $this
     */
    public function minimal() : static {
        $this->addClass('minimal');
        return $this;
    }

    /**
     * Disable this card.
     * @return $this
     */
    public function disable() : static {
        $this->addClass('disable');
        return $this;
    }

}
?>