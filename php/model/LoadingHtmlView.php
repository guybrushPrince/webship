<?php

/**
 * Class LoadingHtmlView
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class LoadingHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some additional classes.
     */
    public function __construct(array|string $classes = array()) {
        parent::__construct(array_merge(['loading'], is_array($classes) ? $classes : [ $classes ]));

        $this->style(Tools::css('.loading', [], 'width: 100%', 'height: 100%',
            'background: rgba(150, 150, 150, 1.0)', 'animation: pulse 2s infinite'));
        $this->style(Tools::css('@-webkit-keyframes pulse', [],
  '0% { background: rgba(150, 150, 150, 1.0); } ' .
            '70% { background: rgba(150, 150, 150, 0.2); } ' .
            '100% { background: rgba(150, 150, 150, 1.0); }'));
        $this->style(Tools::css('@keyframes pulse', [],
            '0% { background: rgba(150, 150, 150, 1.0); } ' .
            '70% { background: rgba(150, 150, 150, 0.2); } ' .
            '100% { background: rgba(150, 150, 150, 1.00); }'));
    }

}
?>