<?php

namespace webship\controller;

use ship\controllers\ShipController;
use ship\interoperability\SLanguage as Language;
use webship\dialogs\ModalHtmlView;
use webship\help\HelpPageHtmlView;

/**
 * Controller BasicsController.
 *
 * <p>A controller for basic functionality.</p>
 *
 * @package webship
 * @subpackage php/basics
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class BasicsController extends ShipController {

    /**
     * Opens the help center as modal.
     * @param string $package The package.
     * @param string|null $subpackage A potential sub package.
     * @param string $doc The document to open.
     * @param int|null $language The language.
     * @return ModalHtmlView
     */
    public function openHelp(string $package, ?string $subpackage, string $doc,
                             ?int $language = Language::GERMAN) : ModalHtmlView {
        return HelpPageHtmlView::createHelpCenter($package, $subpackage, $doc, $language);
    }

    /**
     * @inheritDoc
     * @ignore
     */
    public function getScope() : ?string {
        return BasicFunctions::environment();
    }

}
?>