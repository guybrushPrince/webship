<?php

include_once dirname(__FILE__) . '/ButtonHtmlView.php';

/**
 * Class SubmitHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class SubmitHtmlView extends ButtonHtmlView {

    /**
     * The id of the form to submit.
     * @var string
     */
    protected $formId;

    /**
     * SubmitHtmlView constructor.
     * @param string $id The id.
     * @param string $formId The id of the formular to submit.
     * @param array|string $classes
     */
    public function __construct($id, $formId, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct($id, 'button', array_merge(array('submit'), $classes));
        $this->formId = $formId;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'background: rgba(21, 140, 17, 0.6)',
            'border: none', 'cursor: pointer', 'transition: background 0.5s'));
        $this->style(Tools::css($coreSelector, ':hover', 'background: rgba(21, 140, 17, 1.0)'));
    }

    // Short cuts

    /**
     * Get the form id.
     * @return string|View|null
     */
    public function submitForm() {
        return $this->get($this->formId)["view"];
    }

}
?>