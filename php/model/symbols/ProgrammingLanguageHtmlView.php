<?php

/**
 * Class ProgrammingLanguageHtmlView.
 *
 * @package webship
 * @subpackage php/model/symbols
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class ProgrammingLanguageHtmlView extends ColorHtmlView {

    /**
     * Constructor.
     * @param int $progLang The programming language.
     * @param string[]|string $classes Some classes.
     * @param string|null $title An optional title to replace the name of the programming language.
     */
    public function __construct(int $progLang, array|string $classes = [], ?string $title = null) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'prog-lang';
        $text = $title ?: ProgrammingLanguage::$TITLES[$progLang];
        parent::__construct(Tools::textToColor($text), $classes, $text);
        $this->texts()->text(self::getLanguage(), $text);

        $this->style(Tools::css('.prog-lang', [], 'padding: 5px', 'border-radius: 5px',
            'font-weight: bold', 'border: 1px solid var(--border-3)'));
    }

}
?>