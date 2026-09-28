<?php

namespace webship\help;

use webship\controller\BasicEvents;
use webship\interoperability\WEvent;
use ship\interoperability\SLanguage as Language;
use ship\views\View;
use webship\buttons\StandardButtonHtmlView;
use webship\contents\MarkdownHtmlView;
use webship\dialogs\ModalHtmlView;
use webship\protocols\HtmlActionProtocol;
use webship\WebshipTools;

/**
 * View HelpPageHtmlView.
 *
 * <p>Displays a button that opens a help page rendered using MarkdownHtmlView.</p>
 *
 * <code>
 * HelpPageHtmlView::createIt('sphinx', 'concepts', 'variable');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/help.png" />
 *
 * @title HelpPage
 * @category help
 * 
 * @package webship
 * @subpackage php/model/help
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz, Franziska Lüder
 * @version 1.2.0
 *
 * @view
 * @examples
 *     HelpPageHtmlView::createIt('sphinx', 'concepts', 'variable');
 */
class HelpPageHtmlView extends StandardButtonHtmlView {

    /**
     * Constructor.
     * @param string $package The package.
     * @param string|null $subpackage A potential sub package.
     * @param string $doc The document (concept).
     * @param string $img An image.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string $package, ?string $subpackage, string $doc,
                                 string $img = '/webship/pic/help.svg', array|string $classes = []) {
        parent::__construct('help-' . $package . '-' . $doc, $classes);
        $this->img($img);
        $this->onlyImg(true);

        $this->protocol(WEvent::CLICK)->doIt(BasicEvents::OpenHelp, [
            'package' => $package,
            'subpackage' => $subpackage,
            'doc' => $doc,
            'replace' => 'help-center',
            'add' => 'main-app',
            'error' => 'main-app'
        ], BasicEvents::environment());
        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.help-page', [' ', 'img'], 'position: relative',
            'max-width: 500px', 'width: 100%', 'display: block', 'margin-left: auto', 'margin-right: auto'));
    }

    /**
     * Create a new help center button.
     * @param string $package The package.
     * @param string|null $subpackage A potential sub package.
     * @param string $doc The document (concept).
     * @param string $img An image.
     * @param string[]|string $classes Some classes.
     * @return HelpPageHtmlView
     */
    public static function createIt(string $package, ?string $subpackage, string $doc,
                                    string $img = '/webship/pic/help.svg',
                                    array|string $classes = []) : HelpPageHtmlView {
        return new HelpPageHtmlView($package, $subpackage, $doc, $img, $classes);
    }

    /**
     * Create the help page.
     * @param string $package The package.
     * @param string|null $subpackage A potential sub package.
     * @param string $doc The document (concept).
     * @param int $language The language.
     * @return MarkdownHtmlView
     */
    public static function createHelpPage(string $package, ?string $subpackage, string $doc,
                                          int $language = Language::GERMAN) : MarkdownHtmlView {
        $file = __DIR__ . '/../../../../' . $package . '/docs/' . ($subpackage ? $subpackage . '/' : '') .
            $doc . '_' . Language::BCP47[$language] . '.md';
        if (file_exists($file)) {
            return MarkdownHtmlView::create($file, true, ['help-page'],
                HtmlActionProtocol::getServerURL() . '/' . $package . '/docs/' .
                ($subpackage ? $subpackage . '/' : ''));
        } else return MarkdownHtmlView::create('Cannot find help file ' . $file, false, ['help-page']);
    }

    /**
     * Creates the help center (as modal).
     * @param string $package The package.
     * @param string|null $subpackage A potential sub package.
     * @param string $doc The document (concept).
     * @param int|null $language The language.
     * @return ModalHtmlView
     */
    public static function createHelpCenter(string $package, ?string $subpackage, string $doc,
                                            ?int $language = null) : ModalHtmlView {
        if ($language === null) $language = View::getLanguage();
        $modal = new ModalHtmlView('help-center');
        $modal->header()->texts([
            Language::ENGLISH => 'Coast Help Center',
            Language::GERMAN  => 'Coast Hilfecenter'
        ]);

        $modal->item(self::createHelpPage($package, $subpackage, $doc, $language));
        $modal->protocol(WEvent::ONLOAD)->toggleClass($modal, 'invisible');
        $modal->closer();
        return $modal;
    }
}
?>