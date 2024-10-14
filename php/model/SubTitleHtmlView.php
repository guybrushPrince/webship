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
            'cursor: pointer', 'width: 100%'));
    }
}
?>