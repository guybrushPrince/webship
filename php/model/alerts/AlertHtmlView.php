<?php

namespace webship\alerts;

use BreakwaterUtil;
use DatabaseError;
use Event;
use ParsedownExtra;
use progression\CPTools;
use ship\interoperability\SLanguage as Language;
use ship\views\View;
use Type;
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
 * context.</p
 *
 * @package webship
 * @subpackge php/model/alerts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 */
class AlertHtmlView extends HtmlView {

    /**
     * The box with the content.
     * @var HtmlView
     */
    private HtmlView $box;

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
        self::addStyle(WebshipTools::css('.alert', [], '--alert-border: var(--light-system-background)',
            '--alert-font: var(--font-dark)', '--alert-background: var(--ultra-light-system-background)',
            '--alert-shadow: var(--shadow-flow-4)',
            '--alert-c-border: var(--system-background)', '--alter-c-background: var(--neutral)',
            '--alert-img-filter: none', '--alert-img-filter-hover: none'));

        self::addStyle(WebshipTools::css('.alert:not(.cover)', [], 'padding: 15px',
            'margin-bottom: 20px', 'margin-top: 20px', 'border: 1px solid var(--alert-border)',
            'border-radius: 4px', 'color: var(--alert-font)', 'background-color: var(--alert-background)',
            'position: relative', 'box-sizing: border-box'));
        self::addStyle(WebshipTools::css('.alert:not(.cover).tiny', [], 'font-size: 10px'));
        self::addStyle(WebshipTools::css('.alert.cover', [], 'display: flex',
            'flex-direction: row', 'align-items: center', 'justify-content: center', 'width: 100%',
            'height: 100%', 'top: 0', 'left: 0', 'z-index: 10010', 'background: none'));
        self::addStyle(WebshipTools::css('.alert.screen', [], 'position: fixed'));
        self::addStyle(WebshipTools::css('.alert.full', [], 'position: absolute'));

        // For screen-size alerts.
        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.overlay'], 'top: 0', 'left: 0',
            'width: 100%', 'height: 100%', 'background: var(--window-overlay)', 'z-index: 100011'));
        self::addStyle(WebshipTools::css('.alert.screen', [' > ', '.overlay'], 'position: fixed'));
        self::addStyle(WebshipTools::css('.alert.full', [' > ', '.overlay'], 'position: absolute'));
        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message'], 'width: 75%', 'max-height: 75%',
            'background: var(--alert-background)', 'z-index: 100012', 'padding: 20px',
            '-webkit-box-shadow: var(--alert-shadow)',
            '-moz-box-shadow: var(--alert-shadow)',
            'box-shadow: var--alert-shadow)', 'position: relative'));

        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message', ' > ', '.closer'],
            'position: absolute', 'top: -15px', 'right: -15px', 'width: 30px', 'height: 30px',
            'z-index: 100013', 'background: var(--alert-c-background)', 'border-radius: 45%',
            'justify-content: center', 'cursor: pointer', 'box-sizing: border-box',
            'border: 1px solid var(--alert-c-border)'));
        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message', ' > ', '.closer', ':hover'],
            'background: var(--alert-c-border)'));

        self::addStyle(WebshipTools::css('.alert', [' ', '.ctr', ' > ', 'button.std-button.closer:hover'],
            'background: var(--alert-c-border)'));
        self::addStyle(WebshipTools::css('.alert', [' ', '.ctr', ' > ', 'button.std-button.closer', ' > ', 'img'],
            'filter: var(--alert-img-filter)'));
        self::addStyle(WebshipTools::css('.alert', [' ', '.ctr', ' > ', 'button.std-button.closer:hover', ' > ', 'img'],
            'filter: var(--alert-img-filter-hover)'));

        self::addStyle(WebshipTools::css('.alert.cover', [' ', 'iframe'], 'width: 100%', 'border: 0',
            'background: var(--neutral)', 'border-radius: 10px', 'min-height: 250px',
            'max-height: 500px'));

        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message', ' > ', 'div:last-child'],
            'display: flex', 'flex-direction: column', 'align-items: center'));

        self::addStyle(WebshipTools::css('.alert', [' ', 'pre'], 'display: block'));
        self::addStyle(WebshipTools::css('.alert', [' ', 'pre', ' > ', 'code'], 'width: 100%',
            'border-radius: 10px', 'max-height: 200px', 'overflow: auto', 'overflow: auto'));
        self::addStyle(WebshipTools::css('.alert', [' ', 'code'], 'border: 0',
            'background: var(--neutral)', 'font-family: Monospace', 'font-size: 14px', 'padding: 5px',
            'display: inline-block', 'border-radius: 3px'));
        self::addStyle(WebshipTools::css('.alert.cover', [' ', 'code'], 'display: block', 'height: 400px',
            'font-size: 10px', 'padding: inherit', 'min-height: 100px', 'max-height: 50%'));

        self::addStyle(WebshipTools::css('.alert.invisible', [], 'display: none'));
        self::addStyle(WebshipTools::css('.alert', [' > ', '.closer'], 'position: absolute', 'top: 3px',
            'right: 5px'));
        self::addStyle(WebshipTools::css('.alert:not(.cover).with-image', [], 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'gap: 15px'));
        self::addStyle(WebshipTools::css('.alert:not(.cover).with-image', [' > ', 'img'], 'width: 25px',
            'filter: var(--alert-img-filter)'));
        self::addStyle(WebshipTools::css('.alert:not(.cover).with-image.tiny', [' > ', 'img'], 'width: 15px'));
        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message.with-image'], 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'gap: 15px'));
        self::addStyle(WebshipTools::css('.alert.cover', [' > ', '.a-message.with-image', ' > ', 'img'],
            'width: 25px', 'filter: var(--alert-img-filter)'));

        self::addStyle(WebshipTools::css('.alert.inline', [], 'margin: 0', 'padding: 0 15px'));
    }

    /**
     * Creates an alert html view.
     * @param string[]|string $message The multilingual messages.
     * @param bool $screen If it should cover the screen.
     * @param bool $fullView If it should cover the view.
     * @param AlertHtmlView|null $v A view that is recycled.
     * @param string|null $property A property of the user context, which shall be set.
     * @param string|null $image An image to be shown on the left.
     * @param string[]|string $classes Some classes.
     * @return AlertHtmlView
     * @throws DatabaseError
     */
    protected static function create(array|string $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null,
                                  ?string $image = null, array|string $classes = []) : AlertHtmlView {
        $classes = is_array($classes) ? $classes : [ $classes ];
        if (!is_array($message)) $message = [ View::getLanguage() => $message ];

        if ($property !== null) {
            $show = BreakwaterUtil::getUserParameter($property, Type::BOOLEAN, true);
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
            $overlay = $v->add(new HtmlView('overlay'));
            $overlay->protocol(Event::CLICK)->doIt('Delete', ['view' => $v], 'basics');
            $con = $v->add(new HtmlView('a-message'));
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
            ParsedownExtra::instance()->text($message[self::getLanguage()]) : ''));

        if ($screen || $fullView || $property !== null) {
            $con->ref('alert-view');
            $control = ControlPanelHtmlView::create(1, 1, false)->fix()->plain();
            $con->add($control);
            $closer = $control->add(CloserHtmlView::create('close-alert-' . CPTools::uuid()));
            if ($property) {
                $closer->notices([
                    Language::ENGLISH, 'Do not show this hint anymore.',
                    Language::GERMAN, 'Diesen Hinweis nicht mehr anzeigen.'
                ]);
                $closer->protocol(Event::CLICK)->doIt('ChangeParameter', [
                    'target' => BreakwaterUtil::getUserConfiguration(BreakwaterUtil::getCurrentUser()),
                    'parameter' => $property,
                    'type' => Type::BOOLEAN,
                    'value' => false,
                    'error' => 'main-app'
                ], 'texts')->doIt('Delete', ['view' => $v], 'basics');
            } else {
                $closer->protocol(Event::CLICK)->doIt('Delete', ['view' => $v], 'basics');
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