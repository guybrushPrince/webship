<?php

/**
 * Class EmptyContentHtmlView.
 *
 * @package webship
 * @subpackage php/model
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class EmptyContentHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes.
     */
    private function __construct(string|array $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'empty-content';

        $this->style(Tools::css('.empty-content', [], 'display: flex',
            'flex-direction: column', 'width: 100%', 'align-items: center'));
        $this->style(Tools::css('.empty-content', [' > ', 'img'], 'width: 200px'));
        $this->style(Tools::css('.empty-content.small', [' > ', 'img'], 'width: 100px'));
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
     * Set it to a small image.
     * @return $this
     */
    public function small() : static {
        $this->addClass('small');
        return $this;
    }
}
?>