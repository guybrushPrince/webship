<?php

namespace webship\contents;

use ship\views\TextView;
use ship\views\View;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * Class DescribedHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DescribedHtmlView extends HtmlView {

    /**
     * The description.
     * @var TextView
     */
    protected TextView $descriptionView;

    /**
     * Whether there is a label for.
     * @var string|null
     */
    protected string|null $labelFor;

    /**
     * DescribedHtmlView constructor.
     * @param string[]|string $classes The classes.
     * @param string|null $labelFor The label.
     */
    public function __construct(array|string $classes = [], string|null $labelFor = null) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['described'], $classes));
        $this->descriptionView = $this->texts();
        $this->labelFor = $labelFor;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(WebshipTools::css($coreSelector, '',
            'display: flex', 'flex-wrap: nowrap', 'flex-direction: column', 'padding-top: 20px',
            'align-items: flex-start', 'justify-content: flex-start', 'box-sizing: border-box'));
        $this->style(WebshipTools::css($coreSelector, ' > .described-description', 'width: 100%', 'color: var(--system-background)',
            'font-size: 10px', 'font-weight: 600', 'box-sizing: border-box'));
        $this->style(WebshipTools::css($coreSelector, ' > .described-content', 'width: 100%',
            'box-sizing: border-box'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<div class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">' .
            '<div class="' . implode(' ', array('described-description', $this->descriptionView->id())) . '">' .
            (!is_null($this->labelFor) ? '<label for="' . $this->labelFor . '">' : '') .
            array_shift($this->subviews)->generateSafe() . (!is_null($this->labelFor) ? '</label>' : '') . '</div>' .
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
     * @param integer $language The language.
     * @param string $text The text.
     * @return DescribedHtmlView
     * @see TextView->addText
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
     * @param View $view The view to add.
     * @return View|HtmlView
     * @see View->add
     */
    public function content(View $view) {
        $this->add($view);
        return $view;
    }
}
?>