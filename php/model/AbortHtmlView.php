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

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'background: rgba(168, 44, 22, 0.6)',
            'border: none', 'cursor: pointer'));
        $this->style(Tools::css($coreSelector, ':hover', 'background: rgba(168, 44, 22, 1.0)'));
    }

    // Short cuts

}
?>