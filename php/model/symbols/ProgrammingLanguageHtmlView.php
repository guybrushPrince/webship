<?php

/**
 * View ProgrammingLanguageHtmlView.
 * <p>Creates colored label for the programming language.</p>
 * <img src="http://localhost/coast/webship/modeldoc/programmingLanguage.png" />
 *
 *
 * <code>ProgrammingLanguageHtmlView::create($prog);</code>
 *
 * @package webship
 * @subpackage php/model/symbols
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz, Franziska Lüder
 * @version 1.0.0
 *
 * @view
 * @examples
 *      ProgrammingLanguageHtmlView::create($prog);
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

    /**
     * Return a programming language tag.
     * @param int $progLang The programming language.
     * @param string[]|string $classes Some classes.
     * @param string|null $title An optional title to replace the name of the programming language
     * @return ProgrammingLanguageHtmlView
     */
    public static function create(int $progLang, array|string $classes = [], ?string $title = null): ProgrammingLanguageHtmlView
    {
        return new ProgrammingLanguageHtmlView($progLang, $classes, $title);
    }


}
?>