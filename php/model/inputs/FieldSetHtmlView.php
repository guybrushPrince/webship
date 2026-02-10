<?php

namespace webship\inputs;

use HtmlView;
use Tools;
use View;


/**
 * View FieldSetHtmlView.
 *
 * <p>A HTML field set to collect some input fields within a group.</p>
 * <p>It is possible to refer to a field set as for an input and all input fields will be collected from the set.</p>
 *
 * <code>
 * $fieldSet = FieldSetHtmlView::create('Hello World');
 * $fieldSet->add(LabelledInputHtmlView::createCheckbox('Check 1', 'checkbox-1'));
 * $fieldSet->add(LabelledInputHtmlView::createCheckbox('Check 2', 'checkbox-2', true));
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/fieldset.png" />
 *
 * @package webship
 * @subpackage php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     $fieldSet = FieldSetHtmlView::create('Hello World');
 *     $fieldSet->add(LabelledInputHtmlView::createCheckbox('Check 1', 'checkbox-1'));
 *     $fieldSet->add(LabelledInputHtmlView::createCheckbox('Check 2', 'checkbox-2', true));
 */
class FieldSetHtmlView extends HtmlView {

    /**
     * A legend.
     * @var string[]|null
     */
    private array|null $legend;

    /**
     * FieldSetHtmlView constructor.
     * @param string[]|null $legend A legend for the set.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(?array $legend = null, array|string $classes = []) {
        parent::__construct($classes);
        $this->legend = $legend;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('fieldset', [], 'display: flex', 'flex-wrap: wrap',
            'flex-direction: row', 'align-items: center', 'justify-items: center', 'width: fit-content',
            'width: -moz-fit-content', 'border: none', 'padding: 0', 'margin: 0', 'box-sizing: border-box'));
        self::addStyle(Tools::css('fieldset:not(.plain)', [], '--side-margin: 2px',
            'border: 1px solid var(--border-13)', 'border-radius: 5px', 'padding: 8px 10px',
            'width: calc(100% - 2* var(--side-margin))', 'margin: 0 var(--side-margin)'));
        self::addStyle(Tools::css('fieldset.gap5', [], 'gap:  5px'));
        self::addStyle(Tools::css('fieldset.gap10', [], 'gap: 10px'));
        self::addStyle(Tools::css('fieldset.gap15', [], 'gap: 15px'));
        self::addStyle(Tools::css('fieldset.gap20', [], 'gap: 20px'));
        self::addStyle(Tools::css('fieldset.gap25', [], 'gap: 25px'));
        self::addStyle(Tools::css('fieldset', [' > ', '.entry'], 'display: flex', 'flex-wrap: nowrap',
            'flex-direction: row', 'align-items: center', 'justify-content: flex-start', 'margin: 5px', 'flex: 1 1 0px'));
        self::addStyle(Tools::css('fieldset', [' > ', '.entry', ' ', '[type="checkbox"]'],
            'min-width: 25px', 'max-width: 25px'));
        self::addStyle(Tools::css('fieldset', [' > ', '.entry', ' ', 'label'], 'margin: 0', 'margin-left: 10px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return str_replace('div', 'fieldset', parent::generateOpeningTag()) .
            ($this->legend ? '<legend>' . $this->legend[View::getLanguage()] . '</legend>' : '');
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string  {
        return '</fieldset>';
    }

    /**
     * Return a new fieldset html view.
     * @param string[]|null $legend A legend for the set.
     * @param string[]|string $classes Classes.
     * @return FieldSetHtmlView
     * @deprecated
     */
    public static function _new(?array $legend = null, array|string $classes = []): FieldSetHtmlView {
        return new FieldSetHtmlView($legend, $classes);
    }

    /**
     * Create a fieldset.
     * @param string[]|string|null $legend An optional legend.
     * @param string[]|string $classes Some classes.
     * @return FieldSetHtmlView
     */
    public static function create(array|string|null $legend = null, array|string $classes = []) : FieldSetHtmlView {
        if (is_string($legend)) $legend = [ self::getLanguage() => $legend ];
        return new FieldSetHtmlView($legend, $classes);
    }

    /**
     * Removes the border.
     * @return $this
     */
    public function plain(): static {
        $this->addClass('plain');
        return $this;
    }
}
?>