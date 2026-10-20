<?php

namespace webship\titles;

use webship\containers\ContainerHtmlView;
use webship\contents\SpanTextHtmlView;
use webship\core\HtmlView;
use webship\symbols\ImageHtmlView;
use webship\WebshipTools;

/**
 * View TitleHtmlView.
 *
 * <p>A standardized title for any other view.</p>
 *
 * <code>
 * $title = TitleHtmlView::create('Main Title');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/title.png" />
 *
 * @title Title
 * @category titles
 * 
 * @package webship
 * @subpackage php/model/titles
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $title = TitleHtmlView::create('Main Title');
 */
class TitleHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|null $dataType The type of the title if desired.
     * @param string|null $img The name of an image.
     * @param string|string[] $classes Some classes.
     */
    protected function __construct(string|null $dataType = null, string|null $img = null, string|array $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'title';

        if (!is_null($img) && is_null($dataType)) $this->add(new ImageHtmlView($img));
        if ($dataType) {
            $span = $this->view('title-circle');
            $span->data($dataType);
            if (!is_null($img)) $span->add(new ImageHtmlView($img));
        }

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.title', [],
            'font-family: Roboto, sans-serif', 'text-transform: none', 'padding: 0 0 16px 0',
            'font-size: 1.75em', 'font-weight: lighter', 'border: none', 'display: flex', 'flex-direction: row',
            'align-items: center', 'gap: 10px', '--ws-ti-height: 46px'));
        self::addStyle(WebshipTools::css('.title.small', [], 'padding: 5px 0 5px 5px', 'font-size: 1.25em'));
        self::addStyle(WebshipTools::css('.title', [' > ', '.title-circle'], 'display: inline-block',
            'border-radius: 50%', 'width: 20px', 'height: 20px'));
        self::addStyle(WebshipTools::css('.title', [' > ', 'img'], 'display: inline-block',
            'border-radius: 50%', 'width: 20px', 'height: 20px'));

        self::addStyle(WebshipTools::css('.container.title-container', [' > ', 'nth-child(2)'],
            'height: calc(100% - var(--ws-ti-height))'));
        self::addStyle(WebshipTools::css('.title.title-container', [],
            'height: var(--ws-ti-height)'));
    }

    /**
     * Create a title view with a given title.
     * @param string|string[] $title The title (possibly separated by language).
     * @param string|null $dataType The type of the title if desired.
     * @param string|null $img The name of an image.
     * @param string|string[] $classes Some classes.
     * @return TitleHtmlView
     */
    public static function create(string|array $title, string|null $dataType = null, string|null $img = null,
                                  string|array $classes = []) : TitleHtmlView {
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

    /**
     * Creates a container with the given title that sets the second element to full height.
     * @param string|string[] $title The title (possibly separated by language).
     * @param string|null $dataType The type of the title if desired.
     * @param string|null $img The name of an image.
     * @param string|string[] $classes Some classes.
     * @return ContainerHtmlView
     */
    public static function createTitleContainer(string|array $title, string|null $dataType = null,
                                                string|null $img = null,
                                                string|array $classes = []) : ContainerHtmlView {
        $container = ContainerHtmlView::createIt(['h100', 'title-container']);
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'title-container';
        $container->add(TitleHtmlView::create($title, $dataType, $img, $classes));

        return $container;
    }

}
?>