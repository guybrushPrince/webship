<?php

namespace webship\buttons;

use Exception;
use progression\CPTools;
use Tools;
use View;
use webship\forms\FormHtmlView;

/**
 * View SubmitHtmlView.
 *
 * <p>Displays a submit button which is green by default.</p>
 * <p>It allows to be linked to a form that is submitted (or gets an event) on pressing the enter button.</p>
 *
 * <code>
 * SubmitHtmlView::create()->texts('Submit');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/submit.png" />
 *
 * @package webship
 * @subpackge php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.5.0
 *
 * @version
 * @example
 *     SubmitHtmlView::create()->texts('Submit');
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
    private function __construct(string $id, FormHtmlView|string|null $form, array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct($id, array_merge(['submit'], $classes));
        $this->form = $form;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('button.std-button.submit', [], 'background: var(--button-submit)'));
        self::addStyle(Tools::css('button.std-button.submit', [':hover'],
            'background: var(--dark-system-background)'));
    }

    /**
     * Create a new submit button.
     * @param string|null $id The id.
     * @param string[]|string $classes Some classes.
     * @param FormHtmlView|string|null $form The form or form reference.
     * @return SubmitHtmlView
     * @throws Exception
     */
    public static function create(?string $id = null, array|string $classes = [],
                                  FormHtmlView|string|null $form = null) : SubmitHtmlView {
        return new SubmitHtmlView(is_null($id) ? CPTools::uuid() : $id, $form, $classes);
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