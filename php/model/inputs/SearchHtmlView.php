<?php

namespace webship\inputs;

use Exception;
use ship\interoperability\SLanguage as Language;
use webship\buttons\SubmitHtmlView;
use webship\containers\ContainerHtmlView;
use webship\core\HtmlView;
use webship\symbols\ImageHtmlView;
use webship\WebshipTools;

/**
 * View SearchHtmlView.
 *
 * <p>A search bar that can be used, e.g., for searching things.</p>
 *
 * <code>
 * SearchHtmlView::create('Please search something.', 'You will search for it.');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/search.png" />
 *
 * @name SearchHtmlView
 * @category inputs
 * @package webship
 * @subpackage php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     SearchHtmlView::create('Please search something.', 'You will search for it.');
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
     * @throws Exception
     */
    private function __construct(array|string|null $instructions, array|string|null $notes = null,
                                 array|string $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'search';
        $this->classes[] = 'ver';

        if ($instructions) {
            $this->add(new HtmlView())->texts($instructions);
        }

        $form = $this->form('', '')->ref('search-form');
        $search = $form->add(new ContainerHtmlView(['hor', 'single', 'vcent']));
        $searchField = InputHtmlView::createIt('search-field', 'text');
        $search->add($searchField);
        $searchField->ref('search', 'value');

        if ($instructions) {
            // It is already an array if there is any instruction.
            $searchField->notices($instructions);
        }

        // <div>Icons made by <a href="https://www.flaticon.com/authors/prosymbols" title="Prosymbols">Prosymbols</a>
        // from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
        $this->searchButton = SubmitHtmlView::create('search-button', [], $form);
        $search->add($this->searchButton);
        $this->searchButton->add(new ImageHtmlView('/webship/pic/loupe.svg'));
        if ($notes) {
            $this->searchButton->notices($notes);
        }

        $this->localStyle('--ws-search-text \'' . [
                Language::ENGLISH => 'Search',
                Language::GERMAN  => 'Suche'
            ][self::getLanguage()] . ':\'');

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.search', [' > ', 'form', ' > ', '.container'], 'padding-bottom: 30px',
            'padding-top: 10px'));
        self::addStyle(WebshipTools::css('.search', [' > ', 'form', ' > ', '.container:before'],
            'content: var(--ws-search-text)', 'padding-right: 20px', 'text-transform: uppercase', 'letter-spacing: 3px',
            'color: var(--ws-font-dgray)'));

        self::addStyle(WebshipTools::css('.search', [' ', 'input'], 'width: 75%',
            'border-radius: 5px 0 0 5px', 'border: 1px solid var(--ws-std-bg)', 'transition: 0.5s background'));
        self::addStyle(WebshipTools::css('.search', [' ', 'input:hover'],
            'border: 1px solid var(--ws-std-brd-gray)'));
        self::addStyle(WebshipTools::css('.search', [' ', 'input:focus'],
            'border: 1px solid var(--ws-std-brd-gray)', 'border-radius: 5px 0 0 5px', 'outline: none'));
        self::addStyle(WebshipTools::css('.search', [' ', 'input:hover', ' + ', 'button'],
            'background: var(--ws-std-bg-dark)'));

        self::addStyle(WebshipTools::css('.search', [' ', 'button.std-button.submit'],'width: 50px',
            'height: 50px', 'background: var(--ws-std-bg)', 'border-radius: 0 5px 5px 0',
            'transition: 0.5s background', 'border: none', 'cursor: pointer', 'margin: 0', 'box-shadow: none',
            '-webkit-box-shadow: none'));
        self::addStyle(WebshipTools::css('.search', [' ', 'button.std-button.submit', ' ', 'img'],
            'box-sizing: border-box', 'width: 100%', 'padding: 5px'));
        self::addStyle(WebshipTools::css('.search', [' ', 'button.std-button.submit:hover'],
            'background: var(--ws-std-bg-dark)'));
    }

    /**
     * Constructor.
     * @param string[]|string|null $instructions Instructions that are presented before the search.
     * @param string[]|string|null $notes Notes that are presented over the button.
     * @param string[]|string $classes Some classes.
     * @throws Exception
     */
    public static function create(array|string|null $instructions, array|string|null $notes = null,
                                  array|string $classes = []) : SearchHtmlView {
        return new SearchHtmlView($instructions, $notes, $classes);
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