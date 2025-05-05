<?php

/**
 * Class SubTitleHtmlView.
 *
 * @package webship
 * @subpackage php/model
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class SubTitleHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes.
     */
    public function __construct(string|array $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'sub-title';

        $this->style(Tools::css('.sub-title', [],'font-family: Roboto, sans-serif',
            'text-transform: none', 'font-size: 1.25em', 'font-weight: lighter', 'width: 100%',
            'width: 100%'));
    }

    /**
     * Create a subtitle view.
     * @param string[]|string $texts The (sub) title.
     * @param string|string[] $classes Some classes.
     * @return SubTitleHtmlView
     */
    public static function create(array|string $texts, string|array $classes = []) : SubTitleHtmlView {
        $view = new SubTitleHtmlView($classes);
        $view->texts($texts);
        return $view;
    }
}
?>