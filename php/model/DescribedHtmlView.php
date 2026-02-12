<?php

use ship\views\TextView;
use ship\views\View;

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class DescribedHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DescribedHtmlView extends HtmlView {

    /**
     * The description.
     * @var TextView
     */
    protected $descriptionView;

    /**
     * Whether there is a label for.
     * @var string
     */
    protected $labelFor;

    /**
     * DescribedHtmlView constructor.
     * @param array $classes The classes.
     * @param bool $labelFor The label.
     */
    public function __construct($classes = array(), $labelFor = false) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(array('described'), $classes));
        $this->descriptionView = $this->texts();
        $this->labelFor = $labelFor;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '',
            'display: flex', 'flex-wrap: nowrap', 'flex-direction: column', 'padding-top: 20px' ,
            'align-items: flex-start', 'justify-content: flex-start', 'box-sizing: border-box'));
        $this->style(Tools::css($coreSelector, ' > .described-description', 'width: 100%', 'color: var(--system-background)',
            'font-size: 10px', 'font-weight: 600', 'box-sizing: border-box'));
        $this->style(Tools::css($coreSelector, ' > .described-content', 'width: 100%',
            'box-sizing: border-box'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<div class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">' .
            '<div class="' . implode(' ', array('described-description', $this->descriptionView->id())) . '">' .
            ($this->labelFor ? '<label for="' . $this->labelFor . '">' : '') .
            array_shift($this->subviews)->generateSafe() . ($this->labelFor ? '</label>' : '') . '</div>' .
            '<div class="' . implode(' ', array('described-content')) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</div>' . '</div>';
    }

    // Short cuts

    /**
     * @see TextView->addText
     * @param integer $language The language.
     * @param string $text The text.
     * @return DescribedHtmlView
     */
    public function text($language, $text) {
        $this->descriptionView->addText($language, $text);
        return $this;
    }

    /**
     * Get the description view.
     * @return TextView
     */
    public function descriptionTexts() {
        return $this->descriptionView;
    }

    /**
     * @see View->add
     * @param View $view The view to add.
     * @return View|HtmlView
     */
    public function content(View $view) {
        $this->add($view);
        return $view;
    }

}
?>