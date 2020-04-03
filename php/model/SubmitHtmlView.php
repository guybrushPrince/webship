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
     * @param array $classes
     */
    public function __construct($id, $formId, array $classes = array()) {
        parent::__construct($id, 'button', array_merge(array('submit'), $classes));
        $this->formId = $formId;
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