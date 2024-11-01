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
            'background: var(--dark-system-background)', 'animation: pulse 2s infinite', 'border-radius: 6px'));
        $this->style(Tools::css('@-webkit-keyframes pulse', [],
  '0% { background: var(--loading-dark); } ' .
            '70% { background: var(--loading-light); } ' .
            '100% { background: var(--loading-dark); }'));
        $this->style(Tools::css('@keyframes pulse', [],
            '0% { background: var(--loading-dark); } ' .
            '70% { background: var(--loading-light); } ' .
            '100% { background: var(--loading-dark); }'));
    }

}
?>