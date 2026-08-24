<?php

namespace webship\cards;

use webship\core\HtmlView;
use webship\symbols\ImageHtmlView;
use webship\WebshipTools;

/**
 * View OpeningCardsHtmlView.
 *
 * <p>A container view for managing multiple {@link OpeningCardHtmlView} items in a flex grid layout.</p>
 *
 * <code>
 * $cards = new OpeningCardsHtmlView();
 * $card1 = $cards->card();
 * $card1->setTitle('First Card');
 * </code>
 * 
 * <img src="http://localhost/coast/webship/modeldoc/openingcards.png" />
 *
 * @title OpeningCards
 * @category cards
 * 
 * @package webship
 * @subpackage php/model/cards
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $cards = new OpeningCardsHtmlView();
 *     $card1 = $cards->card();
 *     $card1->setTitle('First Card');
 */
class OpeningCardsHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some additional classes.
     */
    public function __construct(string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'op-cards';
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.op-cards', [], 'display: flex', 'flex-wrap: wrap',
            'flex-direction: row', 'padding: 0 20px', 'gap: 20px'));
        self::addStyle(WebshipTools::css('.op-cards', [' ', '.invisible'], 'display: none'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card'], 'position: relative',
            'width: calc(25%)', 'min-width: 350px', 'transition: 0.5s width', 'margin: 0'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card.wide'], 'width: 100%'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card.inactive', ' > ', '.op-title'],
            'background: var(--ws-inactive)'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card.inactive', ' > ', '.op-title', ':hover'],
            'background: var(--ws-inactive-hvr)'));

        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.title'], 'display: flex',
            'flex-direction: row', 'flex-wrap: wrap', 'font-size: 14px', 'height: 100%', 'cursor: pointer'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card.wide', ' ', '.title'], 'height: initial'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.title', ' > ', 'div:first-child'],
            'width: 100%', 'min-height: 35px'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.title', ' > ', 'div:first-child', ' > ',
            'span:first-child'], 'text-transform: uppercase', 'font-weight: bold', 'letter-spacing: 2px'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.title', ' > ', 'div:first-child', ' > ',
            'span:not(:empty):first-child:after'], 'content: \':\'', 'margin-right: 5px'));

        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-adder', ' > ', '.title', ' > ', 'div:first-child'],
            'width: initial'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-adder', ' > ', '.title'],
            'align-items: center', 'gap: 10px'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-adder', ' > ', '.title', ' ', 'img'],
            'height: 40px'));

        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.title', ' ', '.id:not(:empty)'],
            'position: absolute', 'top: 5px', 'left: 5px', 'font-size: 10px', 'color: var(--ws-font-gray)',
            'width: 100%'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.title', ' ', '.id:not(:empty)', ':before'],
            'content: \'Id:\'', 'margin-right: 5px'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.title', ' ', '.id:empty'],
            'display: none'));

        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.content'], 'padding: 20px'));

        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.content', ' > ', '.op-control'],
            'top: 5px', 'position: absolute', 'height: 20px', 'display: flex', 'flex-direction: row-reverse',
            'right: 5px'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.content', ' > ', '.op-control', ' > ',
            '.op-m-button'], 'position: initial', 'height: 20px', 'width: 20px', 'display: block',
            'line-height: 20px'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.content', ' > ', '.op-control', ' > ',
            '.op-m-button.smaller'], 'height: 15px', 'padding: 2.5px 0', 'width: 15px', 'line-height: 15px'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.content', ' > ', '.op-control', ' > ',
            '.op-m-button', ' ', 'img'], 'height: 100%', 'cursor: pointer', 'opacity: 0.5',
            'transition: opacity 0.5s'));
        self::addStyle(WebshipTools::css('.op-cards', [' > ', '.op-card', ' > ', '.content', ' > ', '.op-control', ' > ',
            '.op-m-button:hover', ' ', 'img'], 'opacity: 1.0'));
    }

    /**
     * Creates a new card in the view.
     * @param bool|null $opened Whether the card is opened, or not.
     * @param bool $openable Whether the card can be opened, or not.
     * @param string|string[] $classes Some classes.
     * @return OpeningCardHtmlView
     */
    public function card(?bool $opened = null, bool $openable = true,
                         string|array $classes = []) : OpeningCardHtmlView {
        $classes = is_array($classes) ? $classes : [$classes];
        if ($opened) $classes[] = 'wide';
        $card = new OpeningCardHtmlView($opened, $openable, $classes);
        $this->add($card);

        return $card;
    }

    /**
     * Creates a new adder card.
     * @param string|string[] $classes Some classes.
     * @param string $image An optional image.
     * @return CardHtmlView
     */
    public function adder(string|array $classes = [], string $image = '/webship/pic/add.svg') : CardHtmlView {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'op-card';
        $classes[] = 'op-adder';
        $adderCard = new CardHtmlView($classes);
        $this->add($adderCard);
        $adderCard->title()->add(new HtmlView())->add(new ImageHtmlView($image));

        return $adderCard;
    }
}
?>