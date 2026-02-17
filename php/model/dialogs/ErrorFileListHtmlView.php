<?php

namespace webship\dialogs;

use Event;
use Tools;
use webship\buttons\StandardButtonHtmlView;
use webship\containers\ContainerHtmlView;
use webship\contents\PlainHtmlView;
use webship\WebshipTools;

/**
 * View ErrorFileListHtmlView.
 *
 * <p>During processes errors may occur resulting in outputs in external files. The {@link ErrorFileListHtmlView} is
 * a button representing occurred errors. Pressing the button results in a full-screen list of files with their
 * content.</p>
 *
 * <code>
 * ErrorFileListHtmlView::createIt('error-list', ['http://localhost/coast/cliff.ini']);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/errorfilelist_1.png" /><br />
 * <img src="http://localhost/coast/webship/modeldoc/errorfilelist_2.png" />
 *
 * @package webship
 * @subpackge php/model/dialogs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     ErrorFileListHtmlView::createIt('error-list', ['http://localhost/coast/cliff.ini'])
 */
class ErrorFileListHtmlView extends StandardButtonHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string $id, array|string $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'gen-error';
        parent::__construct($id, $classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.gen-error', [], 'position: relative'));
        self::addStyle(WebshipTools::css('.gen-error', [' > ', 'div.container'],
            'position: fixed', 'top: 0', 'left: 0', 'width: 100%', 'height: 100%',
            'background: var(--window-overlay)', 'z-index: 100001', 'overflow-y: auto'));
        self::addStyle(WebshipTools::css('.gen-error', [' > ', 'div.container', ' > ', '.container'],
            'position: absolute', 'left: 12.5%', 'top: 10%', 'width: 75%', 'padding: 20px',
            'box-sizing: border-box', 'overflow-y: initial', 'background: var(--neutral)'));
        self::addStyle(WebshipTools::css('.gen-error', [' > ', 'div.invisible'],
            'display: none'));
        self::addStyle(WebshipTools::css('.gen-error', [' ', 'pre'],
            'padding: 10px', 'background: var(--ultra-light-system-background)', 'margin-top: 0',
            'border: 1px solid var(--border-6)', 'box-sizing: border-box', 'font-size: 10px',
            'font-family: monospace', 'text-align: left', 'width: 100%'));
        self::addStyle(WebshipTools::css('.gen-error', [' ', 'pre:before'],
            'content: \'[... last 100 lines ...]\'', 'display: block', 'margin-bottom: 20px'));
        self::addStyle(WebshipTools::css('.gen-error', [' ', '.phase'],
            'font-size: 14px', 'font-weight: bold', 'letter-spacing: 2px'));
        self::addStyle(WebshipTools::css('.gen-error', [' ', '.phase-file'],
            'font-family: monospace', 'font-size: 11px', 'font-weight: bold', 'background: var(--border-6)',
            'padding: 5px'));
        self::addStyle(WebshipTools::css('.gen-error', [' ', '.error'],
            'position: relative'));
        self::addStyle(WebshipTools::css('.gen-error', [' ', '.error:after'],
            'content: \'\'', 'display: block', 'position: absolute', 'top: 0', 'left: 0', 'width: 100%',
            'height: 100%', 'background: var(--error-background-contrast)', 'opacity: 0.4'));
    }

    /**
     * Create a button displaying errors during generation (or similar) in full view.
     * @param string $id The id of the button.
     * @param array $information An array of information presented.
     * @param string[]|string $classes Some classes.
     * @return ErrorFileListHtmlView
     */
    public static function createIt(string $id, array $information, array|string $classes = []) : ErrorFileListHtmlView {
        $view = new ErrorFileListHtmlView($id, $classes);
        $view->onlyImg('/sunstone/pic/error.svg');

        // The error view.
        $v = ContainerHtmlView::createIt('invisible');
        $view->add($v);
        $v->ref('error');
        $view->protocol(Event::CLICK)->doIt('ToggleClass', [
            'view' => $v,
            'class' => 'invisible'
        ], 'basics');

        $pV = $v->container();
        $subViews = [];
        $k = 1;
        foreach ($information as $info) {
            $info = is_array($info) ? $info : [$info];
            foreach ($info as $value) {
                $key = $k;
                $lPV = ContainerHtmlView::createIt(['ver']);
                $content = '';
                if (WebshipTools::endsWith('.log', $value)) {
                    $tex = strpos($value, '-tex') > 0;
                    $sweave = strpos($value, '-sweave') > 0;
                    $lPV->container('phase-file')->texts(basename($value));
                    if (file_exists($value)) {
                        $content = Tools::getLastLinesOfFile($value, 100);
                    }
                    if ($sweave) $key = 's' . $key;
                    if ($tex) $key = 't' . $key;
                    if (strpos($content, 'error')) {
                        $lPV->addClass('error');
                        $key = 'a-' . $key;
                    } else {
                        $key = 'y-' . $key;
                    }
                } else if (WebshipTools::endsWith('.ini', $value)) {
                    $content = Tools::getLastLinesOfFile($value, 100);
                } else {
                    $content = $value . PHP_EOL . PHP_EOL;
                    $key = 'z-' . $key;
                }
                $lPV->add(PlainHtmlView::create('<pre>' . $content . '</pre>'));
                $subViews[$key] = $lPV;
            }
        }
        ksort($subViews);
        foreach ($subViews as $v) $pV->add($v);

        return $view;
    }
}
?>