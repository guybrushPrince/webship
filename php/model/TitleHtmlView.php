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
     * @param string|string[] $classes Some classes.
     */
    public function __construct(string|array $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'title';

        $this->style(Tools::css('.title', [],
            'font-family: Roboto, sans-serif', 'text-transform: none', 'padding: 0 0 16px 0',
            'font-size: 1.75em', 'font-weight: lighter'));
    }

}
?>