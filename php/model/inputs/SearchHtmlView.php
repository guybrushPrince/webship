<?php

/**
 * Class SearchHtmlView.
 *
 * @package webship
 * @subpackage php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class SearchHtmlView extends ContainerHtmlView {

    /**
     * The search button.
     * @var SubmitHtmlView
     */
    private SubmitHtmlView $searchButton;

    /**
     * Constructor.
     * @param string[]|string|null $instructions Instructions that are presented before the search.
     * @param string[]|string|null $notes Notes that are presented over the button.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string|null $instructions, array|string|null $notes = null,
                                array|string $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'search';
        $this->classes[] = 'ver';

        if ($instructions) {
            if (!is_array($instructions)) $instructions = [ self::getLanguage(), $instructions ];
            $this->add(new HtmlView())->texts()->text(self::getLanguage(), $instructions[self::getLanguage()]);
        }

        $form = $this->form('', '')->ref('search-form');
        $search = $form->add(new ContainerHtmlView(['hor', 'single', 'vcent']));
        $searchField = new InputHtmlView('search-field', 'search-field', "text");
        $search->add($searchField);
        $searchField->ref('search', 'value');

        if ($instructions) {
            // It is already an array if there is any instruction.
            $searchField->notice(self::getLanguage(), $instructions[self::getLanguage()]);
        }

        // <div>Icons made by <a href="https://www.flaticon.com/authors/prosymbols" title="Prosymbols">Prosymbols</a>
        // from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
        $this->searchButton = new SubmitHtmlView('search-button', $form);
        $search->add($this->searchButton);
        $this->searchButton->add(new ImageHtmlView('/vaultui/pic/loupe.png'));
        if ($notes) {
            if (!is_array($notes)) $notes = [ self::getLanguage() => $notes ];
            $this->searchButton->notice(self::getLanguage(), $notes[self::getLanguage()]);
        }

        $this->localStyle('--text: \'' .
            [
                Language::ENGLISH => 'Search',
                Language::GERMAN  => 'Suche'
            ][self::getLanguage()] .
            ':\'');

        $this->style(Tools::css('.search', [' > ', 'form', ' > ', '.container'], 'padding-bottom: 30px',
            'padding-top: 10px'));
        $this->style(Tools::css('.search', [' > ', 'form', ' > ', '.container:before'],
            'content: var(--text)', 'padding-right: 20px', 'text-transform: uppercase', 'letter-spacing: 3px',
            'color: var(--font-light)'));

        $this->style(Tools::css('.search', [' ', 'input'], 'width: 75%',
            'border-radius: 5px 0 0 5px', 'border: 1px solid var(--system-background)', 'transition: 0.5s background'));
        $this->style(Tools::css('.search', [' ', 'input:hover'],
            'border: 1px solid var(--border-5)'));
        $this->style(Tools::css('.search', [' ', 'input:focus'],
            'border: 1px solid var(--border-5)', 'border-radius: 5px 0 0 5px', 'outline: none'));
        $this->style(Tools::css('.search', [' ', 'input:hover', ' + ', 'button'],
            'background: var(--dark-system-background)'));

        $this->style(Tools::css('.search', [' ', 'button.std-button.submit'],'width: 50px',
            'height: 50px', 'background: var(--system-background)', 'border-radius: 0 5px 5px 0',
            'transition: 0.5s background', 'border: none', 'cursor: pointer', 'margin: 0', 'box-shadow: none',
            '-webkit-box-shadow: none'));
        $this->style(Tools::css('.search', [' ', 'button.std-button.submit', ' ', 'img'],
            'box-sizing: border-box', 'width: 100%', 'padding: 5px'));
        $this->style(Tools::css('.search', [' ', 'button.std-button.submit:hover'],
            'background: var(--dark-system-background)'));
    }

    /**
     * Get the search button (to add the functionality).
     * @return SubmitHtmlView
     */
    public function button() : SubmitHtmlView {
        return $this->searchButton;
    }

}
?>