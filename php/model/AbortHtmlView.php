<?php

include_once dirname(__FILE__) . '/ButtonHtmlView.php';

/**
 * Class AbortHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class AbortHtmlView extends ButtonHtmlView {

    /**
     * AbortHtmlView constructor.
     * @param string $id The id.
     * @param array $classes
     */
    public function __construct($id, array $classes = array()) {
        parent::__construct($id, 'reset', array_merge(array('reset'), $classes));

        /*$coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'box-sizing: border-box', 'padding: 5px',
            'height: 50px', 'width: 100%', 'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));*/
    }

    // Short cuts

}
?>