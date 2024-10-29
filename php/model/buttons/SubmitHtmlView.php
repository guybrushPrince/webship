<?php

/**
 * Class SubmitHtmlView.
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 */
class SubmitHtmlView extends StandardButtonHtmlView {

    /**
     * The form / reference of the form to submit.
     * @var string|FormHtmlView|null
     */
    protected string|FormHtmlView|null $form;

    /**
     * SubmitHtmlView constructor.
     * @param string $id The id.
     * @param string|FormHtmlView|null $form The form or form reference.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(string $id, FormHtmlView|string|null $form, array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct($id, array_merge(['submit'], $classes));
        $this->form = $form;

        $this->style(Tools::css('button.std-button.submit', [], 'background: var(--button-submit)'));
        $this->style(Tools::css('button.std-button.submit', [':hover'],
            'background: var(--dark-system-background)'));
    }

    /**
     * Get the form.
     * @return string|View|null|FormHtmlView
     */
    public function submitForm() : string|View|null|FormHtmlView {
        if (is_string($this->form)) return $this->get($this->form)["view"];
        else return $this->form;
    }
}
?>