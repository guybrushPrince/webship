<?php

namespace webship\symbols;

use Exception;
use ship\interoperability\SLanguage as Language;
use webship\contents\PlainHtmlView;
use webship\core\HtmlView;
use webship\interoperability\ITextEditable;
use webship\WebshipTools;

/**
 * Class NamelessHtmlView.
 *
 * @package webship
 * @subpackage php/model/symbols
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class NamelessHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param ITextEditable $target The target.
     * @param string|string[]|null $fields A field or some fields.
     * @param int $language The language.
     * @param bool $textBox If it refers to a text box or to a description.
     * @param string|null $defaultText A default text if it shall be one set.
     * @param string|null $img The image.
     * @param string[]|string $classes Some classes.
     * @throws Exception
     */
    private function __construct(ITextEditable $target,
                                 string|array|null $fields = null, int $language = Language::GERMAN,
                                 bool $textBox = true, ?string $defaultText = null,
                                 ?string $img = '/webship/pic/unknown.svg', array|string $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'unknown';
        $classes[] = 'really';
        parent::__construct($classes);

        if (!$fields) $fields = ['header '];
        else if (!is_array($fields)) $fields = [$fields];

        $code = '';
        foreach ($fields as $field) {
            $code .= self::createLinkableText($target, $field, $language, $textBox, $defaultText);
        }
        $this->add(PlainHtmlView::create($code));
        if ($img) {
            $imageView = new ImageHtmlView($img);
            $this->add($imageView);
            $imageView->notice(Language::ENGLISH, 'You do not have specified any text yet');
            $imageView->notice(Language::GERMAN, 'Du hast noch keinen Text spezifiziert');
        }

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.unknown', [], 'width: -moz-fit-content', 'width: fit-content',
            'display: flex', 'flex-direction: row'));
        self::addStyle(WebshipTools::css('span', [' > ', '.unknown'], 'display: inline-flex'));
        self::addStyle(WebshipTools::css('.unknown', [' > ', 'span:empty'], 'display: none'));
        self::addStyle(WebshipTools::css('.unknown', [' > ', 'img'], 'height: 10px'));
        self::addStyle(WebshipTools::css('.unknown.hidden', [' > ', 'img'], 'display: none'));
        self::addStyle(WebshipTools::css('.unknown', [' > ', 'span:not(:empty)', ' ~ ', 'img'],
            'display: none'));
        self::addStyle(WebshipTools::css('.unknown', [' > ', 'span:not(:empty)', ' ~ ', 'span'],
            'display: none'));
    }

    /**
     * Create a nameless view.
     * @param ITextEditable $target The target.
     * @param string|string[]|null $fields A field or some fields.
     * @param int $language The language.
     * @param bool $textBox If it refers to a text box or to a description.
     * @param string|null $defaultText A default text if it shall be one set.
     * @param string|null $img The image.
     * @param bool|null $invisible Set invisible if empty.
     * @param string[]|string $classes Some classes.
     * @return string
     * @throws Exception
     */
    public static function create(ITextEditable $target,
                                  string|array|null $fields = null, int $language = Language::GERMAN,
                                  bool $textBox = true, ?string $defaultText = null,
                                  ?string $img = '/webship/pic/unknown.svg',
                                  ?bool $invisible = false,
                                  array|string $classes = []) : string {
        if ($invisible) {
            if (!is_array($classes)) $classes = [$classes];
            $classes[] = 'hidden';
        }
        return (new NamelessHtmlView($target, $fields, $language, $textBox, $defaultText, $img, $classes))->stringify();
    }

    /**
     * Creates a purely textual chip without any functionality.
     * @param int|null $language The language.
     * @param array|string|null $alternative An alternative text that is missing.
     * @return string
     */
    public static function createChip(int|null $language = null, array|string|null $alternative = null) : string {
        if (is_null($language)) $language = self::getLanguage();
        if (is_null($alternative)) $alternative = 'TEXT';
        else if (is_array($alternative)) $alternative = strtoupper($alternative[$language]);
        else $alternative = strtoupper($alternative);
        return [
            Language::ENGLISH => '<span class="intext intext-without">WITHOUT ' . $alternative . '</span>',
            Language::GERMAN => '<span class="intext intext-without">OHNE ' . $alternative . '</span>'
        ][$language];
    }

    /**
     * Get the view as string.
     * @return string
     * @throws Exception
     */
    public function stringify() : string {
        return $this->generate();
    }

    /**
     * Create a linked text if the corresponding entry, key, etc. are known.
     * @param string $entry The entry.
     * @param string|int $objectKey The key of the object.
     * @param string $field The field.
     * @param int $language The language.
     * @param string $text An optional text.
     * @return string
     */
    public static function createLinkedText(string $entry, string|int $objectKey, string $field, int $language,
                                            string $text) : string {
        return '<span data-' . $entry . '="' . $objectKey . '" ' .
            'data-field="' . $field . '" ' . 'data-lang="' . $language . '">' . $text . '</span>';
    }

    /**
     * Create linkable text.
     * @param ITextEditable $target The target.
     * @param string $field The field.
     * @param int $language The language.
     * @param array|string|int|bool $params Some additional parameters.
     * @param string|null $text The text (if available).
     * @return string
     * @throws Exception
     */
    public function createLinkableText(ITextEditable $target, string $field,
                                       int $language = Language::GERMAN, array|string|int|bool $params = [],
                                       ?string $text = null) : string {
        return $target->createLinkableText($field, $language, $params, $text);
    }
}
?>