<?php

/**
 * Class StandardHtmlView.
 *
 * @package webship
 * @subpackge php/model/containers
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class StandardHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes.
     */
    public function __construct(string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'std';
        parent::__construct($classes);

        $this->style(Tools::css('.std', [], 'width: 100%', 'padding: 20px 20px',
            'box-sizing: border-box', 'position: relative'));
    }
}
?>