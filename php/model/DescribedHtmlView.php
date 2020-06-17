<?php

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
     */
    public function __construct(array $classes = array(), $labelFor = false) {
        parent::__construct(array_merge(array('described'), $classes));
        $this->descriptionView = $this->texts();
        $this->labelFor = $labelFor;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '',
            'display: flex', 'flex-wrap: nowrap', 'flex-direction: column', 'padding-top: 20px' ,
            'align-items: flex-start', 'justify-content: flex-start'));
        $this->style(Tools::css($coreSelector, ' > .described-description', 'width: 100%', 'color: rgba(200, 200, 200, 1.0)',
            'font-size: 10px', 'font-weight: 600'));
        $this->style(Tools::css($coreSelector, ' > .described-content', 'width: 100%'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<div class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">' .
            '<div class="' . implode(' ', array('described-description', $this->descriptionView->id())) . '">' .
            ($this->labelFor ? '<label for="' . $this->labelFor . '">' : '') .
            array_shift($this->subviews)->generateSafe() . ($this->labelFor ? '</label>' : '') . '</div>' .
            '<div class="' . implode(' ', array('described-content')) . '">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
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