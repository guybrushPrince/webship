<?php

namespace webship\dialogs;

use Language;
use ParsedownExtra;
use webship\core\HtmlView;

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


        $message = $this->add(new HtmlView('dialog-message'));
        $title = $message->add(new HtmlView('d-title'));
        $title->texts()->
        text(Language::ENGLISH, 'Note')->
        text(Language::GERMAN, 'Hinweis');
        $textView = $message->add(new HtmlView('text'));
        $parseDown = new ParsedownExtra();
        $textView->texts()->text(self::getLanguage(), $parseDown->text($texts[self::getLanguage()]));
        $this->choicesBox = $message->add(new HtmlView('choices'));
    }

    /**
     * Generates a new option.
     * @param int $nr The number of the option.
     * @param array $texts The texts of the option.
     * @return HtmlView
     */
    public function choice(int $nr, array $texts) : HtmlView {
        $v = $this->choicesBox->add(new HtmlView(['choice', 'c-' . $nr]));
        $this->choices[$nr] = $v;
        $v->texts()->text(self::getLanguage(), $texts[self::getLanguage()]);
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