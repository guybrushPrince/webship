<?php

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
     * @param ITextBoxUser|DescribableObject $target The target.
     * @param string|string[]|null $fields A field or some fields.
     * @param int $language The language.
     * @param bool $textBox If it refers to a text box or to a description.
     * @param string|null $defaultText A default text if it shall be one set.
     * @param string|null $img The image.
     * @param string[]|string $classes Some classes.
     * @throws DatabaseError
     */
    private function __construct(ITextBoxUser|DescribableObject $target,
                                string|array|null $fields = null, int $language = Language::GERMAN,
                                bool $textBox = true, ?string $defaultText = null,
                                ?string $img = '/webship/pic/unknown.svg', array|string $classes = []) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'unknown';
        $classes[] = 'really';
        parent::__construct($classes);

        if (!$fields) $fields = [ 'header '];
        else if (!is_array($fields)) $fields = [ $fields ];

        $code = '';
        foreach ($fields as $field) {
            $code .= self::createLinkableText($target, $field, $language, $textBox, $defaultText);
        }
        $this->add(PlainHtmlView::create($code));
        if ($img) {
            $imageView = new ImageHtmlView($img);
            $this->add($imageView);
            $imageView->notice(Language::ENGLISH, 'You do not have specified any text yet');
            $imageView->notice(Language::GERMAN,  'Du hast noch keinen Text spezifiziert');
        }

        self::addCSS();
    }

    /**
     * Generate the CSS code.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.unknown', [], 'width: -moz-fit-content', 'width: fit-content',
            'display: flex', 'flex-direction: row'));
        self::addStyle(Tools::css('.unknown', [' > ', 'span:empty'], 'display: none'));
        self::addStyle(Tools::css('.unknown', [' > ', 'img'], 'height: 10px'));
        self::addStyle(Tools::css('.unknown.hidden', [' > ', 'img'], 'display: none'));
        self::addStyle(Tools::css('.unknown', [' > ', 'span:not(:empty)', ' ~ ', 'img'],
            'display: none'));
        self::addStyle(Tools::css('.unknown', [' > ', 'span:not(:empty)', ' ~ ', 'span'],
            'display: none'));
    }

    /**
     * Create a nameless view.
     * @param ITextBoxUser|DescribableObject $target The target.
     * @param string|string[]|null $fields A field or some fields.
     * @param int $language The language.
     * @param bool $textBox If it refers to a text box or to a description.
     * @param string|null $defaultText A default text if it shall be one set.
     * @param string|null $img The image.
     * @param bool|null $invisible Set invisible if empty.
     * @param string[]|string $classes Some classes.
     * @return string
     * @throws DatabaseError
     */
    public static function create(ITextBoxUser|DescribableObject $target,
                                  string|array|null $fields = null, int $language = Language::GERMAN,
                                  bool $textBox = true, ?string $defaultText = null,
                                  ?string $img = '/webship/pic/unknown.svg',
                                  ?bool $invisible = false,
                                  array|string $classes = []) : string {
        if ($invisible) {
            if (!is_array($classes)) $classes = [ $classes ];
            $classes[] = 'hidden';
        }
        return (new NamelessHtmlView($target, $fields, $language, $textBox, $defaultText, $img, $classes))->stringify();
    }

    /**
     * Get the view as string.
     * @return string
     */
    public function stringify() : string {
        return $this->generate();
    }

    /**
     * Create linkable text.
     * @param ITextBoxUser|DescribableObject $target The target.
     * @param string $field The field.
     * @param int $language The language.
     * @param bool $textBox If the text box or the description shall be accessed.
     * @param string|null $text The text (if available).
     * @return string
     * @throws DatabaseError
     */
    public function createLinkableText(ITextBoxUser|DescribableObject $target, string $field,
                                       int $language = Language::GERMAN, bool $textBox = true,
                                       ?string $text = null) : string {
        $entry = ($textBox ? 'box' : 'desc');

        // "Repair" objects that do not have a text box or description yet.
        if ($textBox && !$target->getTextBoxEntry()) {
            if (get_class($target)::exists($target->getKey()) && AccessCreationHelper::hasWriteAccess($target) &&
                AccessCreationHelper::hasReadAccess($target)) {
                $textController = new TextController();
                $textController->changeTextField($target, 'header', $language, null);
            } else return '';
        } else if (!$textBox && (!$target->getDescription() || !$target->getLanguageDescription($language, true))) {
            if (AccessCreationHelper::hasReadAccess($target) && AccessCreationHelper::hasWriteAccess($target)) {

                $target = get_class($target)::get($target->getId());

                Persistence::instance()->startTransaction();
                $addDescriptionAccess = !$target->getDescription();
                if (!$addDescriptionAccess) $addLanguageAccess = !($target->getLanguageDescription($language));
                else $addLanguageAccess = true;

                $target->setDescriptionText($field, $language, '');

                $persistence = Persistence::instance();
                $persistence->startTransaction();
                if ($addDescriptionAccess) {
                    AccessCreationHelper::inheritOrAddAccessRights($target, $target->getDescription());
                    $target->getDescription()->create();
                }
                if ($addLanguageAccess) {
                    AccessCreationHelper::inheritOrAddAccessRights($target, $target->getLanguageDescription($language));
                    $target->getLanguageDescription($language)->create();
                    $target->getDescription()->updateClose();
                } else {
                    $target->getLanguageDescription($language)->updateClose();
                }
                $target->updateClose();
                Persistence::instance()->endTransaction();
            } else return '';
        }


        $obj = ($textBox ? $target->getTextBoxEntry() : $target->getLanguageDescription($language));
        $text = ($text !== null) ?: '';

        return '<span data-' . $entry . '="' . ShipperRegister::convertKey($obj->getKey()) . '" ' .
            'data-field="' . $field . '" ' . 'data-lang="' . $language . '">' . $text . '</span>';
    }

}
?>