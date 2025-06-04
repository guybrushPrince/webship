<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class FormHtmlView.
 *
 * @package webship
 * @subpackage php/model
 *
 * @version 1.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class FieldSetHtmlView extends HtmlView {

    /**
     * A legend.
     * @var string[]|null
     */
    private ?array $legend = null;

    /**
     * FieldSetHtmlView constructor.
     * @param string[]|null $legend A legend for the set.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(?array $legend = null, string|array $classes = []) {
        parent::__construct($classes);
        $this->legend = $legend;

        $this->style(Tools::css('fieldset', [], 'display: flex', 'flex-wrap: wrap',
            'flex-direction: row', 'align-items: center', 'justify-items: center', 'width: fit-content',
            'width: -moz-fit-content', 'border: none', 'padding: 0', 'margin: 0', 'box-sizing: border-box'));
        $this->style(Tools::css('fieldset:not(.plain)', [], 'border: 1px solid var(--border-13)',
            'border-radius: 5px', 'padding: 8px 10px', 'width: 100%', 'margin: 0 2px'));
        $this->style(Tools::css('fieldset.gap5' , [], 'gap:  5px'));
        $this->style(Tools::css('fieldset.gap10', [], 'gap: 10px'));
        $this->style(Tools::css('fieldset.gap15', [], 'gap: 15px'));
        $this->style(Tools::css('fieldset.gap20', [], 'gap: 20px'));
        $this->style(Tools::css('fieldset.gap25', [], 'gap: 25px'));
        $this->style(Tools::css('fieldset', [' > ', '.entry'], 'display: flex', 'flex-wrap: nowrap',
            'flex-direction: row', 'align-items: center', 'justify-content: flex-start', 'margin: 5px', 'flex: 1 1 0px'));
        $this->style(Tools::css('fieldset', [' > ', '.entry', ' ', '[type="checkbox"]'],
            'min-width: 25px', 'max-width: 25px'));
        $this->style(Tools::css('fieldset', [' > ', '.entry', ' ', 'label'], 'margin: 0', 'margin-left: 10px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return str_replace('div', 'fieldset', parent::generateOpeningTag()) .
            ($this->legend ? '<legend>' . $this->legend[View::getLanguage()] . '</legend>' : '');
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '</fieldset>';
    }

    /**
     * Return a new fieldset html view.
     * @param string[]|null $legend A legend for the set.
     * @param string[]|string $classes Classes.
     * @return FieldSetHtmlView
     */
    public static function _new(?array $legend = null, string|array $classes = []) : FieldSetHtmlView {
        return new FieldSetHtmlView($legend, $classes);
    }

    /**
     * Removes the border.
     * @return $this
     */
    public function plain() : static {
        $this->addClass('plain');
        return $this;
    }

}
?>