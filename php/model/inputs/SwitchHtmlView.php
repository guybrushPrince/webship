<?php

namespace webship\inputs;

use ship\views\View;
use webship\WebshipTools;

/**
 * View SwitchHtmlView.
 *
 * <p>A checkbox in form of a switch to turn on or off things.</p>
 *
 * <code>
 * SwitchHtmlView::create('switch-1');
 * SwitchHtmlView::create('switch-2', true);
 * SwitchHtmlView::create('switch-3', true)->mini();
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/switch.png" />
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.5.0
 *
 * @view
 * @examples
 *     SwitchHtmlView::create('switch-1');
 *     SwitchHtmlView::create('switch-2', true);
 *     SwitchHtmlView::create('switch-3', true)->mini();
 */
class SwitchHtmlView extends InputHtmlView {

    /**
     * A list of classes to add it to the surrounding view.
     * @var string[]
     */
    private array $localClasses;

    /**
     * Constructor.
     * @param string $id The id.
     * @param string $name The name.
     * @param string[]|string $classes The classes.
     */
    private function __construct(string $id, string $name, array|string $classes = []) {
        parent::__construct($id, $name, 'checkbox');
        $this->localClasses = array_merge([ 'switch' ], is_array($classes) ? $classes : [ $classes ]);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {

        /*, 'height: 22px', 'width: 40px'));
        $this->style(Tools::css('.page-con', [' > ', '.switch', ' ', '.slider'], 'border-radius: 11px'));
        $this->style(Tools::css('.page-con', [' > ', '.switch', ' ', '.slider:before'],
            'height: 14px', 'width: 14px'));
        $this->style(Tools::css('.page-con', [' > ', '.switch', ' ', 'input:checked', ' + ', '.slider:before'],
            'transform: translateX(18px)' ));*/

        self::addStyle(WebshipTools::css('.switch', '', 'position: relative',
            'display: inline-block',
            '--ws-sw-width: 60px', '--ws-sw-height: 34px', '--ws-sw-gap-left: 4px', '--ws-sw-gap-bottom: 4px',
            '--ws-sw-ball: calc(var(--ws-sw-height) - var(--ws-sw-gap-bottom))',
            'width: var(--ws-sw-width)', 'height: var(--ws-sw-height)'));
        self::addStyle(WebshipTools::css('.switch', [' ', 'input'], 'opacity: 0', 'width: 0', 'height: 0'));
        self::addStyle(WebshipTools::css('.switch', [' ', 'input[type="checkbox"]'], 'height: 0'));
        self::addStyle(WebshipTools::css('.switch', [' ', '.slider'], 'position: absolute', 'cursor: pointer',
            'top: 0', 'left: 0', 'right: 0', 'bottom: 0', 'background-color: #ccc', '-webkit-transition: .4s',
            'transition: .4s', 'border-radius: var(--ws-sw-height)'));
        self::addStyle(WebshipTools::css('.switch', [' ', '.slider', ':before'], 'position: absolute',
            'content: ""',
            'height: var(--ws-sw-ball)', 'width: var(--ws-sw-ball)', 'left: var(--ws-sw-gap-left)', 'bottom: var(--ws-sw-gap-bottom)',
            'background-color: white',
            '-webkit-transition: .4s', 'transition: .4s', 'border-radius: 50%'));
        self::addStyle(WebshipTools::css('.switch', [' ', 'input:checked', ' + ', '.slider'],
            'background-color: var(--ws-slider-checked)'));
        self::addStyle(WebshipTools::css('.switch', [' ', 'input:focus', ' + ', '.slider'],
            'box-shadow: 0 0 1px var(--ws-slider-checked)'));
        self::addStyle(WebshipTools::css('.switch', [' ', 'input:checked', ' + ', '.slider:before'],
            '-webkit-transform: translateX(var(--ws-sw-ball))', '-ms-transform: translateX(var(--ws-sw-ball))',
            'transform: translateX(var(--ws-sw-ball))'));
        self::addStyle(WebshipTools::css('.switch', [' ', 'input:disabled', ' + ', '.slider'],
            'background-color: var(--ws-std-bg)', 'opacity: 0.5', 'cursor: not-allowed'));

        self::addStyle(WebshipTools::css('.switch.mini', '', '--ws-sw-width: 30px',
            '--ws-sw-height: 17px', '--ws-sw-gap-bottom: 0', '--ws-sw-gap-left: -2px', '--ws-sw-ball: var(--ws-sw-height)'));
        self::addStyle(WebshipTools::css('.switch.mini', [' > ', '.slider::before'],
            'border: 1px solid var(--ws-slider-checked)', 'box-sizing: border-box'));
        self::addStyle(WebshipTools::css('.switch.mini', [' > ', 'input:not(:checked)', ' + ', '.slider::before'],
            'border-color: 1px solid #ccc'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<label class="' . implode(' ', $this->localClasses) . '"' .
            ($this->notices ? ' title="' . (array_key_exists(View::getLanguage(), $this->notices) ?
                    $this->notices[View::getLanguage()] : array_pop($this->notices)) . '"' : '') .
            '>' . parent::generateOpeningTag();
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return parent::generateClosingTag() . '<span class="slider"></span></label>';
    }

    /**
     * Create a switch.
     * @param string $id The id.
     * @param bool|null $checked The current value.
     * @param string[]|string $classes The classes.
     * @return SwitchHtmlView
     */
    public static function create(string $id, ?bool $checked = false, string|array $classes = []) : SwitchHtmlView {
        $switch = new SwitchHtmlView($id, $id, $classes);
        $switch->texts(!$checked ? '' : 'checked');
        return $switch;
    }

    /**
     * Creates a mini switch.
     * @return $this
     */
    public function mini() : static {
        $this->localClasses[] = 'mini';
        return $this;
    }
}
?>