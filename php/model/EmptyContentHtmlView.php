<?php
/**
 * View EmptyContentHtmlView.
 *
 * <p>Presents a full-width <em>empty content</em> view with a simple, empty-content image along with an optional
 * {@link InfoHtmlView}.</p>
 * <p>You can choose between a medium (200p width) and a small image (100p width) by using
 * {@link EmptyContentHtmlView::small()}.</p>
 *
 * <code>
 * $emptyContentView = EmptyContentHtmlView::create();
 * $emptyContentView = EmptyContentHtmlView::create('This is an EmptyContentView!');
 * $emptyContentView = EmptyContentHtmlView::create([Language::ENGLISH => 'Hello empty world!', Language::GERMAN => 'Hallo leere Welt!']);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/emptycontent.png" />
 *
 * @package webship
 * @subpackage php/model
 *
 * @version 1.1.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Mihailo Jovic
 *
 * @view
 * @examples
 *     $emptyContentView = EmptyContentHtmlView::create();
 *     $emptyContentView = EmptyContentHtmlView::create('This is an EmptyContentView!');
 *     $emptyContentView = EmptyContentHtmlView::create([Language::ENGLISH => 'Hello empty world!', Language::GERMAN => 'Hallo leere Welt!']);
 *
 */
class EmptyContentHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes.
     */
    private function __construct(string|array $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'empty-content';

        self::addCSS();
    }

    /**
     * Creates an empty content view.
     * @param string|string[]|null $info (Optional) An info text.
     * @param string|string[] $classes Some classes.
     * @return EmptyContentHtmlView
     * @throws DatabaseError
     */
    public static function create(string|array|null $info = null, string|array $classes = []) : EmptyContentHtmlView {
        $view = new EmptyContentHtmlView($classes);
        $view->add(new ImageHtmlView('/sunstone/pic/box.png'));
        if ($info) {
            if (is_string($info)) $info = [ self::getLanguage() => $info ];
            $view->add(InfoHtmlView::create($info));
        }
        return $view;
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.empty-content', [], 'display: flex',
            'flex-direction: column', 'width: 100%', 'align-items: center'));
        self::addStyle(Tools::css('.empty-content', [' > ', 'img'], 'width: 200px'));
        self::addStyle(Tools::css('.empty-content.small', [' > ', 'img'], 'width: 100px'));

    }

    /**
     * Set it to a small image.
     * @return $this
     */
    public function small() : static {
        $this->addClass('small');
        return $this;
    }
}
?>