<?php

namespace webship\forms;

use HtmlView;
use ship\interoperability\IShippable;
use ship\interoperability\ShipperRegister;
use Tools;
use webship\inputs\InputHtmlView;
use webship\inputs\SelectHtmlView;

/**
 * View FormLabelHtmlView.
 *
 * <p>A label for an {@link InputHtmlView} or a {@link SelectHtmlView}. A simple string can also be used as a
 * reference.</p>
 *
 * <code>
 * $input = InputHtmlView::createIt('input-1', 'text');
 * $label = FormLabelHtmlView::create($input)->texts('Input:');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/formlabel.png" />
 *
 * @package webship
 * @subpackge php/model/forms
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     $input = InputHtmlView::createIt('input-1', 'text');
 *     $label = FormLabelHtmlView::create($input)->texts('Input:');
 */
class FormLabelHtmlView extends HtmlView {

    /**
     * The input field for which this label is for.
     * @var string|null
     */
    private ?string $for;

    /**
     * Constructor.
     * @param string|null $for The reference to the input for which this label is for.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(?string $for = null, array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'form-label' ], $classes));
        $this->for = $for;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('label.form-label', [], 'display: block', 'width: 100%',
            'margin-top: 10px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $tag = parent::generateOpeningTag();
        return str_replace('<div', '<label' . ($this->for ? ' for="' . $this->for . '"' : ''), $tag);
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</label>';
    }

    /**
     * Create a new form label.
     * @param IShippable|SelectHtmlView|InputHtmlView|string|null $for The reference to the input for which this label is for.
     * @param string[]|string $classes Some classes.
     * @return FormLabelHtmlView
     */
    public static function create(IShippable|SelectHtmlView|InputHtmlView|string|null $for = null,
                                  array|string $classes = []) : FormLabelHtmlView {
        if ($for instanceof IShippable) $for = ShipperRegister::getObjectString($for);
        else if ($for instanceof InputHtmlView || $for instanceof SelectHtmlView) $for = $for->getId();
        return new FormLabelHtmlView($for, $classes);
    }
}
?>