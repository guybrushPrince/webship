<?php

/**
 * Class StandardButtonHtmlView.
 *
 * @package webship
 * @subpackge php/model/buttons
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class StandardButtonHtmlView extends ButtonHtmlView {

    /**
     * Constructor.
     * @param string|null $id The id.
     * @param string|array $classes Some classes.
     */
    private function __construct(?string $id = null, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct($id, 'button', array_merge(['std-button'], $classes));

        $this->style(Tools::css('button.std-button', [], 'display: flex', 'flex-direction: row',
            'align-items: center', 'justify-content: center',
            'width: 175px', 'margin-right: 20px', 'min-height: 50px', 'cursor: pointer',
            'border: 1px solid var(--dark-system-background)', 'background: var(--card-background)',
            'color: var(--main-font)', 'font-size: 16px', 'text-align: center',
            'font-family: Roboto Condensed, Roboto, sans-serif', 'position: relative', 'margin: .5rem 0 1rem 0',
            '-webkit-transition: -webkit-box-shadow .25s', 'transition: box-shadow .25s', 'border-radius: 2px',
            '-webkit-box-shadow: var(--shadow-flow-1)', 'box-shadow: var(--shadow-flow-1)',
            'transition: background 0.5s', 'border-radius: 5px'));
        $this->style(Tools::css('button.std-button', [':hover'],
            'background: var(--dark-system-background)'));
        $this->style(Tools::css('.std-button', [' > ', 'img'], 'max-height: 100%'));
    }

    /**
     * Creates a new standard button.
     * @param string|null $id The id.
     * @param string|string[] $classes Some classes.
     * @return StandardButtonHtmlView
     */
    public static function create(?string $id = null, string|array $classes = []) : StandardButtonHtmlView {
        return new StandardButtonHtmlView($id, $classes);
    }

    /**
     * Adds an image to the button.
     * @param string $image The button.
     * @return StandardButtonHtmlView $this
     */
    public function img(string $image) : static {
        $this->add(new ImageHtmlView($image));
        if (count($this->subviews) > 1) {
            $imgView = array_pop($this->subviews);
            array_unshift($this->subviews, $imgView);
        }
        return $this;
    }

}
?>