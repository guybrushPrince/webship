<?php

namespace webship\core;

use Exception;
use ship\interoperability\SLanguage;
use webship\WebshipTools;
use webship\protocols\HtmlActionProtocol;

/**
 * View ApplicationHtmlView.
 *
 * <p>The most basic application view, which contains the HTML, HEAD, BODY, etc. tags. Each WebShip application
 * should start with this view as it also sets basic CSS and JS.</p>
 *
 * <code>
 * $app = ApplicationHtmlView::create('Hello World', 'favicon.ico');
 * $app->css('helloworld.css');
 * </code>
 *
 * @title Application
 * @category core
 * 
 * @package webship
 * @subpackage php/model/core
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.4.0
 *
 * @view
 * @examples
 *     $app = ApplicationHtmlView::create('Hello World', 'favicon.ico');
 *     $app->css('helloworld.css');
 */
class ApplicationHtmlView extends HtmlView {

    /**
     * Links to external JS scripts.
     * @var string[]
     */
    private array $scripts = [];

    /**
     * Links to external CSS files.
     * @var string[]
     */
    private array $css = [];

    /**
     * The header of the app.
     * @var string[]
     */
    private array $appHeader;

    /**
     * A relative path to an icon.
     * @var string|null
     */
    private string|null $icon;

    /**
     * The server URL.
     * @var string
     */
    private string $server;

    /**
     * @inheritDoc
     */
    private function __construct(array|string $appHeader, string|null $icon = null,
                                 array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['application'], $classes));

        $this->appHeader = is_string($appHeader) ? [ self::getLanguage() => $appHeader ] : $appHeader;
        $this->icon = $icon;

        // Initialize some information.
        $this->server = HtmlActionProtocol::getServerURL();

        if (isset($_SESSION)) {
            if (array_key_exists('objects', $_SESSION)) unset($_SESSION['objects']);
            if (array_key_exists('references', $_SESSION)) unset($_SESSION['references']);
            if (array_key_exists('styles', $_SESSION)) unset($_SESSION['styles']);
            if (array_key_exists('templates', $_SESSION)) unset($_SESSION['templates']);
        }

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('html', [], 'margin: 0', 'height: 100%', 'width: 100%'));
        self::addStyle(WebshipTools::css('.application', [], 'font-family: sans-serif',
            'font-size: 14px', 'margin: 0', 'height: 100%', 'width: 100%'));
        self::addStyle(WebshipTools::css('script, style', [], 'display: none !important'));
    }

    /**
     * Create an application view.
     * @param string[]|string $appHeader The title/header of the app (displayed in the browser).
     * @param string|null $icon A relative path to an optional icon (displayed in the browser).
     * @param string[]|string $classes Some additional classes.
     * @return ApplicationHtmlView
     */
    public static function create(array|string $appHeader, string|null $icon = null,
                                  array|string $classes = []) : ApplicationHtmlView {
        $application = new ApplicationHtmlView($appHeader, $icon, $classes);
        $application->script('/webship/js/jquery-4.0.0.min.js');
        $application->script('/webship/js/ship.js');
        $application->script('/webship/js/shiptabs.js');

        $application->css('/webship/css/webship.css');

        $apiURL = HtmlActionProtocol::getApiURL();
        $relApiURL = HtmlActionProtocol::getRelativeApiURL();

        $application->js('context["server"] = "' . HtmlActionProtocol::getServerURL() . '";');
        $application->js('context["api"] = () => "/' . $apiURL . $relApiURL . '";');
        $application->js('context["baseApi"] = "/' . $apiURL . '";');
        $application->js('context["relApi"] = "/' . $relApiURL . '";');

        return $application;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function generateOpeningTag() : string {
        $code = '<!DOCTYPE html>' . '<html lang="' . SLanguage::BCP47[self::getLanguage()] . '">';
        $code .= '<head>';
        $code .= '<title>' . $this->appHeader[self::getLanguage()] . '</title>';
        $code .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">';
        // Create the CSS links
        $code .= implode(array_map(function ($style) {
            return '<link href="' . $style . '" rel="stylesheet"/>';
        }, $this->css));
        $code .= '<style type="text/css">' . $this->generateStyle() . '</style>';
        // Create the JS links
        $code .= implode(array_map(function ($script) {
            return '<script src="' . $script . '"></script>';
        }, $this->scripts));
        if (!is_null($this->icon)) {
            $code .= '<link rel="shortcut icon" type="image/x-icon" href="' . HtmlActionProtocol::getServerURL() . '/' . $this->icon . '">';
        }
        $code .= '</head>';
        $code .= '<body class="' . implode(' ', array_merge($this->classes, [ $this->id() ])) . '">';
        return $code;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function generateClosingTag() : string {
        return '</body><script>' . $this->generateJS() . '</script></html>';
    }

    //////////////////////////////////
    // Shortcuts
    //////////////////////////////////

    /**
     * Add a link to a script.
     * @param string $script
     */
    public function script(string $script) : void {
        if (WebshipTools::startsWith('/', $script)) {
            $script = $this->server . $script;
        }
        $this->scripts[] = $script;
    }

    /**
     * Add a link to a style.
     * @param string $style
     */
    public function css(string $style) : void {
        if (WebshipTools::startsWith('/', $style)) {
            $style = $this->server . $style;
        }
        $this->css[] = $style;
    }

}
?>