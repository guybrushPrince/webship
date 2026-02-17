<?php

namespace webship\symbols;

use ProgrammingLanguage;
use Tools;
use webship\WebshipTools;

/**
 * View ProgrammingLanguageHtmlView.
 *
 * <p>Creates colored label for a given programming language of enumeration {@link ProgrammingLanguage}. The view
 * is a specialization of the {@link ColorHtmlView} and, thus, provides its functionality.</p>
 *
 * <img src="http://localhost/coast/webship/modeldoc/programminglanguage.png" />
 *
 * <code>
 * ProgrammingLanguageHtmlView::create(ProgrammingLanguage::PHP);
 * </code>
 *
 * @package webship
 * @subpackge php/model/symbols
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Franziska Lüder
 * @version 1.2.0
 *
 * @view
 * @examples
 *      ProgrammingLanguageHtmlView::create(ProgrammingLanguage::PHP);
 */
class ProgrammingLanguageHtmlView extends ColorHtmlView {

    /**
     * Constructor.
     * @param int $progLang The programming language(one of {@link ProgrammingLanguage}).
     * @param string[]|string $classes Some classes.
     * @param string[]|string|null $title An optional title to replace the name of the programming language.
     */
    private function __construct(int $progLang, array|string $classes = [], array|string|null $title = null) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'prog-lang';
        $text = $title ?: ProgrammingLanguage::$TITLES[$progLang];
        parent::__construct(Tools::textToColor($text), $classes, $text);
        $this->texts($text);

        self::addCSS();
    }

    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.prog-lang', [], 'padding: 5px', 'border-radius: 5px',
            'font-weight: bold', 'border: 1px solid var(--border-3)'));
    }

    /**
     * Return a programming language tag.
     * @param int $progLang The programming language (one of {@link ProgrammingLanguage}).
     * @param string[]|string|null $title An optional title to replace the name of the programming language.
     * @param string[]|string $classes Some classes.
     * @return ProgrammingLanguageHtmlView
     */
    public static function create(int $progLang, array|string|null $title = null,
                                  array|string $classes = []) : ProgrammingLanguageHtmlView {
        return new ProgrammingLanguageHtmlView($progLang, $classes, $title);
    }
}
?>