<?php

/**
 * Class TitleHtmlView.
 *
 * @package webship
 * @subpackage php/model
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TitleHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|null $dataType The type of the title if desired.
     * @param string|null $img The name of an image.
     * @param string|string[] $classes Some classes.
     */
    public function __construct(?string $dataType = null, ?string $img = null, string|array $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'title';

        if ($img) $this->add(new ImageHtmlView($img));
        if ($dataType) $this->add(new SpanTextHtmlView('title-circle'))->data($dataType);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.title', [],
            'font-family: Roboto, sans-serif', 'text-transform: none', 'padding: 0 0 16px 0',
            'font-size: 1.75em', 'font-weight: lighter', 'border: none', 'display: flex', 'flex-direction: row',
            'align-items: center', 'gap: 10px'));
        self::addStyle(Tools::css('.title.small', [], 'padding: 5px 0 5px 5px', 'font-size: 1.25em'));
        self::addStyle(Tools::css('.title', [' > ', '.title-circle'], 'display: inline-block',
            'border-radius: 50%', 'width: 20px', 'height: 20px'));
        self::addStyle(Tools::css('.title', [' > ', 'img'], 'display: inline-block',
            'border-radius: 50%', 'width: 20px', 'height: 20px'));
    }

    /**
     * Create a title view with a given title.
     * @param string|string[] $title The title (possibly separated by language).
     * @param string|null $dataType The type of the title if desired.
     * @param string|null $img The name of an image.
     * @param string|string[] $classes Some classes.
     * @return TitleHtmlView
     */
    public static function create(string|array $title, ?string $dataType = null, ?string $img = null,
                                  string|array $classes = []) : TitleHtmlView {
        if (!is_array($title)) $title = [ self::getLanguage() => $title ];
        $view = new TitleHtmlView($dataType, $img, $classes);
        $view->texts($title);
        return $view;
    }

    /**
     * Makes the title a little bit smaller.
     * @return $this
     */
    public function small() : static {
        $this->classes[] = 'small';
        return $this;
    }

}
?>