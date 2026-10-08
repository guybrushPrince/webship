<?php

namespace webship\alerts;

use Exception;
use webship\controller\BasicEvents;
use webship\interoperability\IClickAway;
use webship\interoperability\WEvent;
use ParsedownExtra;
use ship\interoperability\SLanguage as Language;
use ship\views\View;
use webship\buttons\CloserHtmlView;
use webship\core\HtmlView;
use webship\menus\ControlPanelHtmlView;
use webship\symbols\ImageHtmlView;
use webship\WebshipTools;

/**
 * View AlertHtmlView.
 *
 * <p>A basic <em>alert</em> view, which can be specialized for more specific cases.
 * It can cover the screen or view, can display an optional image, and can get (and set) parameters from the user
 * context.</p>
 *
 * <code>
 * AlertHtmlView::create('This is a basic alert message.');
 * AlertHtmlView::create('This is a screen alert message.', true);
 * AlertHtmlView::create('This is a full view alert message.', false, true);
 * </code>
 * 
 * <img src="http://localhost/coast/webship/modeldoc/alert.png" />
 *
 * @title Alert
 * @category alerts
 * 
 * @package webship
 * @subpackage php/model/alerts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     AlertHtmlView::create('This is a basic alert message.');
 *     AlertHtmlView::create('This is a screen alert message.', true);
 *     AlertHtmlView::create('This is a full view alert message.', false, true);
 */
class AlertHtmlView extends HtmlView {

    /**
     * The box with the content.
     * @var HtmlView
     */
    private HtmlView $box;

    private static IClickAway|null $clickAwayEffect = null;

    /**
     * AlertHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    protected function __construct(array|string $classes = []) {
        parent::__construct(array_merge(['alert'], is_array($classes) ? $classes : [ $classes ]));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.alert', [], '--ws-alert-border: var(--ws-std-bg-light)',
            '--ws-alert-font: var(--ws-font-dark)', '--ws-alert-background: var(--ws-std-bg-ultra)',
            '--ws-alert-shadow: var(--ws-front-shadow)',
            '--ws-alert-c-border: var(--ws-std-bg)', '--ws-alert-c-background: var(--ws-neutral)',
            '--ws-alert-img-filter: none', '--ws-alert-img-filter-hover: none'));

        self::addStyle(WebshipTools::css('.alert:not(.cover)', [], 'padding: 15px',
            'margin-bottom: 20px', 'margin-top: 20px', 'border: 1px solid var(--ws-alert-border)',
            'border-radius: 4px', 'color: var(--ws-alert-font)', 'background-color: var(--ws-alert-background)',
            'position: relative', 'box-sizing: border-box'));
        self::addStyle(WebshipTools::css('.alert:not(.cover).tiny', [], 'font-size: 10px'));
        self::addStyle(WebshipTools::css('.alert.cover', [], 'display: flex',
            'flex-direction: row', 'align-items: center', 'justify-content: center', 'width: 100%',
            'height: 100%', 'top: 0', 'left: 0', 'z-index: 10010', 'background: none'));
        self::addStyle(WebshipTools::css('.alert.screen', [], 'position: fixed'));
        self::addStyle(WebshipTools::css('.alert.full', [], 'position: absolute'));

        // For screen-size alerts.
        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.overlay'], 'top: 0', 'left: 0',
            'width: 100%', 'height: 100%', 'background: var(--ws-screen-overlay)', 'z-index: 100011'));
        self::addStyle(WebshipTools::css('.alert.screen', [' > ', '.overlay'], 'position: fixed'));
        self::addStyle(WebshipTools::css('.alert.full', [' > ', '.overlay'], 'position: absolute'));
        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message'], 'width: 75%', 'max-height: 75%',
            'background: var(--ws-alert-background)', 'z-index: 100012', 'padding: 20px',
            '-webkit-box-shadow: var(--ws-alert-shadow)',
            '-moz-box-shadow: var(--ws-alert-shadow)',
            'box-shadow: var--ws-alert-shadow)', 'position: relative'));

        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message', ' > ', '.closer'],
            'position: absolute', 'top: -15px', 'right: -15px', 'width: 30px', 'height: 30px',
            'z-index: 100013', 'background: var(--ws-alert-c-background)', 'border-radius: 45%',
            'justify-content: center', 'cursor: pointer', 'box-sizing: border-box',
            'border: 1px solid var(--ws-alert-c-border)'));
        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message', ' > ', '.closer', ':hover'],
            'background: var(--ws-alert-c-border)'));

        self::addStyle(WebshipTools::css('.alert', [' ', '.ctr', ' > ', 'button.std-button.closer:hover'],
            'background: var(--ws-alert-c-border)'));
        self::addStyle(WebshipTools::css('.alert', [' ', '.ctr', ' > ', 'button.std-button.closer', ' > ', 'img'],
            'filter: var(--ws-alert-img-filter)'));
        self::addStyle(WebshipTools::css('.alert', [' ', '.ctr', ' > ', 'button.std-button.closer:hover', ' > ', 'img'],
            'filter: var(--ws-alert-img-filter-hover)'));

        self::addStyle(WebshipTools::css('.alert', [' ', 'iframe'], 'width: 100%', 'border: 0',
            'background: var(--ws-neutral)', 'border-radius: 10px', 'min-height: 250px',
            'max-height: 500px'));

        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message', ' > ', 'div:last-child'],
            'display: flex', 'flex-direction: column', 'align-items: center'));

        self::addStyle(WebshipTools::css('.alert', [' ', 'pre'], 'display: block'));
        self::addStyle(WebshipTools::css('.alert', [' ', 'pre', ' > ', 'code'], 'width: 100%',
            'border-radius: 10px', 'max-height: 200px', 'overflow: auto', 'overflow: auto'));
        self::addStyle(WebshipTools::css('.alert', [' ', 'code'], 'border: 0',
            'background: var(--ws-neutral)', 'font-family: Monospace', 'font-size: 14px', 'padding: 5px',
            'display: inline-block', 'border-radius: 3px'));
        self::addStyle(WebshipTools::css('.alert.cover', [' ', 'code'], 'display: block', 'height: 400px',
            'font-size: 10px', 'padding: inherit', 'min-height: 100px', 'max-height: 50%'));

        self::addStyle(WebshipTools::css('.alert.invisible', [], 'display: none'));
        self::addStyle(WebshipTools::css('.alert', [' > ', '.closer'], 'position: absolute', 'top: 3px',
            'right: 5px'));
        self::addStyle(WebshipTools::css('.alert:not(.cover).with-image', [], 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'gap: 15px'));
        self::addStyle(WebshipTools::css('.alert:not(.cover).with-image', [' > ', 'img'], 'width: 25px',
            'filter: var(--ws-alert-img-filter)'));
        self::addStyle(WebshipTools::css('.alert:not(.cover).with-image.tiny', [' > ', 'img'], 'width: 15px'));
        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message.with-image'], 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'gap: 15px'));
        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message.with-image', ' > ', 'img'],
            'width: 25px', 'filter: var(--ws-alert-img-filter)'));

        self::addStyle(WebshipTools::css('.alert.inline', [], 'margin: 0', 'padding: 0 15px'));
    }

    /**
     * Initialize the effect if an alert is clicked away.
     * @param IClickAway $clickAway The click away implementation.
     * @return void
     */
    public static function initClickAway(IClickAway $clickAway) : void {
        self::$clickAwayEffect = $clickAway;
    }

    /**
     * Creates an alert html view.
     * @param string[]|string $message The multilingual messages.
     * @param bool $screen If it should cover the screen.
     * @param bool $fullView If it should cover the view.
     * @param AlertHtmlView|null $v A view that is recycled.
     * @param string|null $alertId A property of the user context, which shall be set.
     * @param string|null $image An image to be shown on the left.
     * @param string[]|string $classes Some classes.
     * @return AlertHtmlView
     * @throws Exception
     */
    protected static function create(array|string $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $alertId = null,
                                  ?string $image = null, array|string $classes = []) : AlertHtmlView {
        $classes = is_array($classes) ? $classes : [ $classes ];
        if (!is_array($message)) $message = [ View::getLanguage() => $message ];

        if (!is_null(self::$clickAwayEffect) && !is_null($alertId)) {
            $show = self::$clickAwayEffect->shallShow($alertId);
            if (!$show) {
                if (!$v) return new AlertHtmlView(array_merge($classes, [ 'invisible' ]));
                else {
                    $v->addClass('invisible');
                    return $v;
                }
            }
        }

        if (!$v) $v = new AlertHtmlView(array_merge($classes, $screen ? ['cover', 'screen'] : ($fullView ? ['cover', 'full'] : [])));
        else {
            if ($screen || $fullView) $v->addClass('cover');
            if ($screen) $v->addClass('screen');
            if ($fullView) $v->addClass('full');
        }

        $con = $v;
        if ($screen || $fullView) {
            $v->ref('alert');
            $overlay = $v->view('overlay');
            $overlay->protocol(WEvent::CLICK)->doIt(BasicEvents::Delete, [
                'view' => $v
            ], BasicEvents::environment());
            $con = $v->view('a-message');
            $con->ref('alert-content');
        }
        $v->box = $con;
        if ($image) {
            $con->addClass('with-image');
            $con->add(new ImageHtmlView($image));
            $textView = $con->add(new HtmlView());
            $v->box = $textView;
        } else $textView = $con;

        $textView->texts((array_key_exists(self::getLanguage(), $message) ?
            ParsedownExtra::instance()->text('<div>' . $message[self::getLanguage()] . '</div>') : ''));

        if ($screen || $fullView || $alertId !== null) {
            $con->ref('alert-view');
            $control = ControlPanelHtmlView::create(1, 1, false)->fix()->plain();
            $con->add($control);
            $closer = CloserHtmlView::create('close-alert-' . WebshipTools::uuid());
            $control->add($closer);
            if (!is_null($alertId) && !is_null(self::$clickAwayEffect)) {
                $closer->notices([
                    Language::ENGLISH, 'Do not show this hint anymore.',
                    Language::GERMAN, 'Diesen Hinweis nicht mehr anzeigen.'
                ]);
                $closer->protocol(WEvent::CLICK)->
                    append(self::$clickAwayEffect->doNotShowAnymore($alertId, $closer))->
                    doIt(BasicEvents::Delete, ['view' => $v], BasicEvents::environment());
            } else {
                $closer->protocol(WEvent::CLICK)->doIt(BasicEvents::Delete, ['view' => $v], BasicEvents::environment());
            }
        }

        return $v;
    }

    /**
     * Get the box.
     * @return HtmlView
     */
    public function box() : HtmlView {
        return $this->box;
    }

    /**
     * Creates an inline alert.
     * @return $this
     */
    public function inline() : static {
        $this->addClass('inline');
        return $this;
    }

    /**
     * Creates a tiny alert.
     * @return $this
     */
    public function tiny() : static {
        $this->addClass('tiny');
        return $this;
    }
}
?>