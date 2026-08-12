<?php

namespace webship\dialogs;

use ParsedownExtra;
use ship\interoperability\SLanguage as Language;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * Class DialogHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DialogHtmlView extends HtmlView {

    /**
     * The message view.
     * @var HtmlView
     */
    private HtmlView $choicesBox;

    /**
     * The choices.
     * @var HtmlView[]
     */
    private array $choices;

    /**
     * DialogHtmlView constructor.
     * @param string[] $texts The texts.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array $texts, array|string $classes = []) {
        parent::__construct(array_merge(['dialog'], is_array($classes) ? $classes : []));

        $message = new HtmlView('dialog-message');
        $this->add($message);
        $title = new HtmlView('d-title');
        $message->add($title);
        $title->texts([
            Language::ENGLISH => 'Note',
            Language::GERMAN  => 'Hinweis'
        ]);
        $textView = new HtmlView('text');
        $message->add($textView);
        $parseDown = new ParsedownExtra();
        $textView->texts($parseDown->text($texts[self::getLanguage()]));
        $this->choicesBox = new HtmlView('choices');
        $message->add($this->choicesBox);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.dialog', [], 'position: fixed', 'top: 0', 'left: 0',
            'width: 100%', 'height: 100%',
            '--ws-dialog-overlay: var(--ws-screen-overlay)', 'background: var(--ws-dialog-overlay)', 'z-index: 10000000',
            '--ws-dialog-shadow: var(--ws-front-shadow)'));
        self::addStyle(WebshipTools::css('.dialog', [' > ', '.dialog-message'], 'position: absolute',
            'top: calc((100% - 150px) / 2)', 'left: calc((100% - 300px) / 2)',
            'width: 300px', 'height: 150px', 'border-color: var(--ws-border-error)',
            'color: var(--ws-font-error)', 'background-color: var(--ws-background-error)', 'border: 1px solid',
            'border-radius: 5px', '-webkit-box-shadow: var(--ws-dialog-shadow)',
            '-moz-box-shadow: var(--ws-dialog-shadow)', 'box-shadow: var--ws-dialog-shadow)'));
        self::addStyle(WebshipTools::css('.dialog', [' ', 'p'], 'margin: 0'));
        self::addStyle(WebshipTools::css('.dialog', [' > ', '.dialog-message', ' > ', '.d-title'],
            'padding: 10px', 'background-color: var(--ws-cbackground-error)', 'border-radius: 5px 5px 0 0',
            'font-weight: bold'));
        self::addStyle(WebshipTools::css('.dialog', [' > ', '.dialog-message', ' > ', '.text'],
            'padding: 10px'));
        self::addStyle(WebshipTools::css('.dialog', [' > ', '.dialog-message', ' > ', '.choices'],
            'position: absolute', 'bottom: 0', 'display: flex', 'flex-direction: row', 'width: 100%',
            'height: 30px', 'border-radius: 0 0 5px 5px', 'border-top: 1px solid var(--ws-border-error)'));
        self::addStyle(WebshipTools::css('.dialog', [' > ', '.dialog-message', ' > ', '.choices', ' > ',
            '.choice'],
            'display: flex', 'flex-direction: row', 'flex: 1 1 0', 'justify-content: center',
            'align-items: center', 'cursor: pointer', 'transition: background 0.5s'));
        self::addStyle(WebshipTools::css('.dialog', [' > ', '.dialog-message', ' > ', '.choices', ' > ',
            '.choice', ':hover'],
            'background-color: var(--ws-cbackground-error)'));
        self::addStyle(WebshipTools::css('.dialog', [' > ', '.dialog-message', ' > ', '.choices', ' > ',
            '.choice', ':last-child'],
            'border-radius: 0 0 5px 0'));
        self::addStyle(WebshipTools::css('.dialog', [' > ', '.dialog-message', ' > ', '.choices', ' > ',
            '.choice', ':first-child'],
            'border-radius: 0 0 0 5px'));
    }

    /**
     * Generates a new option.
     * @param int $nr The number of the option.
     * @param array $texts The texts of the option.
     * @return HtmlView
     */
    public function choice(int $nr, array $texts) : HtmlView {
        $v = new HtmlView(['choice', 'c-' . $nr]);
        $this->choicesBox->add($v);
        $this->choices[$nr] = $v;
        $v->texts($texts);
        $v->ref("choice-" . $nr);
        return $v;
    }

    /**
     * Get the view with the choice.
     * @param int $nr The number.
     * @return HtmlView|null
     */
    public function getChoice(int $nr) : ?HtmlView {
        if (array_key_exists($nr, $this->choices)) return $this->choices[$nr];
        else return null;
    }
}
?>