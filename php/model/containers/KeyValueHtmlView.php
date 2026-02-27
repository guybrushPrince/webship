<?php

namespace webship\containers;

use ship\views\View;
use webship\core\HtmlView;
use webship\forms\FormLabelHtmlView;
use webship\inputs\InputHtmlView;
use webship\inputs\SelectHtmlView;
use webship\inputs\SwitchHtmlView;
use webship\WebshipTools;

/**
 * View KeyValueHtmlView.
 *
 * <p>Presents the key and a view with the corresponding value in one line.
 * There is a column on the left for the key and a column on the right for the value. Both use 100 % width.
 * You have the option to show a frame or not and to change the size of the key and value fields.</p>
 * <img src="http://localhost/coast/webship/modeldoc/keyvalue.png" />
 *
 * <code>
 * KeyValueHtmlView::createString('Key', 'Value', false);
 * </code>
 *
 * @package webship
 * @subpackge php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     KeyValueHtmlView::createString('Key', 'Value', false);
 */
class KeyValueHtmlView extends HtmlView {

    /**
     * The value view.
     * @var HtmlView
     */
    private HtmlView $valueView;

    /**
     * Constructor.
     * @param View|string|string[] $key The key.
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of key field.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @param string|null $for An attribute for.
     * @param bool $forceKeySize Whether empty spaces shall be replaced.
     * @param string|string[]|null $afterText Some texts (as units) to present after the input.
     */
    protected function __construct(View|array|string $key, bool $frame = true, int $keySize = 30,
                                   ?int $valueSize = null,
                                   array|string $classes = [], ?string $for = null, bool $forceKeySize = true,
                                   string|array|null $afterText = null) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'kv';
        if ($frame) $classes[] = 'frame';
        parent::__construct($classes);

        if ($valueSize === null) $valueSize = 100 - $keySize;
        $keyRatio = round($keySize / ($keySize + $valueSize), 2) * 100;
        $valueRatio = round($valueSize / ($keySize + $valueSize), 2) * 100;

        if ($afterText) $valueRatio -= 5;

        if (!$for) $keyView = $this->add(new HtmlView(['k']));
        else $keyView = $this->add(FormLabelHtmlView::create($for, ['k']));
        if ($key instanceof View) $keyView->add($key);
        else {
            if (!is_array($key)) $key = [self::getLanguage() => $key];
            foreach ($key as $l => $k) $keyView->texts()->text($l, $forceKeySize ? str_replace(' ', '&nbsp;', $k) : $k);
        }

        $this->valueView = new HtmlView(['v']);
        $this->add($this->valueView);

        if ($afterText) {
            if (!is_array($afterText)) $afterText = [self::getLanguage() => $afterText];
            $afterView = $this->add(new HtmlView('ka'));
            $afterView->texts($afterText);
        }

        $this->localStyle('--ws-kr: ' . $keyRatio . '%');
        $this->localStyle('--ws-vr: ' . $valueRatio . '%');

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.kv', [], 'display: flex', 'flex-direction: row', 'flex-wrap: nowrap',
            'width: 100%', 'align-items: center', 'padding: 10px', 'box-sizing: border-box', 'gap: 0 10px',
            'box-sizing: border-box'));
        self::addStyle(WebshipTools::css('.kv.frame', [], 'border: 1px solid var(--ws-std-bg-ultra)'));
        self::addStyle(WebshipTools::css('.kv', [' > ', '.k'], 'display: flex', 'flex-direction: row',
            'align-items: center', 'justify-content: flex-start', 'margin: 0', 'width: var(--ws-kr)'));
        self::addStyle(WebshipTools::css('.kv', [' > ', '.v'], 'display: flex', 'flex-direction: row',
            'align-items: center', 'justify-content: flex-start', 'margin: 0', 'width: var(--ws-vr)'));
        self::addStyle(WebshipTools::css('.kv', [' > ', '.v', ' > ', 'input[type="checkbox"]'], 'width: 25px'));
        self::addStyle(WebshipTools::css('.kv', [' > ', '.ka'], 'width: 5%'));
        self::addStyle(WebshipTools::css('.kv.frame', [' + ', '.kv.frame'], 'border-top: none'));
        self::addStyle(WebshipTools::css('.kv.ver', [], 'flex-direction: column'));
        self::addStyle(WebshipTools::css('.kv.ver', [' > ', '*'], 'width: 100%'));
        self::addStyle(WebshipTools::css('.kv.np', [], 'padding: 0'));

        self::addStyle(WebshipTools::css('.kv.disabled', [], 'position: relative', 'cursor: not-allowed'));
        self::addStyle(WebshipTools::css('.kv.disabled', [':after'], 'content: ""', 'display: block',
            'position: absolute', 'width: 100%', 'height: 100%', 'top: 0', 'left: 0', 'background: var(--ws-disabled-overlay)',
            'border-radius: 5px'));
    }

    /**
     * Creates a key-value where the value is a string.
     * @param View|string[]|string $key The key (possibly in different languages).
     * @param string $value The value (as string).
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param bool $swap Whether key and value shall be swapped.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createString(View|array|string $key, string $value,
                                        bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                        bool $swap = false, array|string $classes = []) : KeyValueHtmlView {
        if ($swap) {
            $tmp = $valueSize;
            $valueSize = $keySize;
            $keySize = $tmp;
            $view = new KeyValueHtmlView($key, $frame, $valueSize, $keySize, $classes);
            $view->valueView->texts($value);
            $view->subviews = [$view->subviews[1], $view->subviews[0]];
        } else {
            $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes);
            $view->valueView->texts($value);
        }
        return $view;
    }

    /**
     * Creates a key-value where the value is a view.
     * @param View|string[]|string $key The key (possibly in different languages).
     * @param HtmlView $value The value (as view).
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createView(View|array|string $key, HtmlView $value,
                                      bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                      array|string $classes = []) : KeyValueHtmlView {
        $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes);
        $view->valueView->add($value);
        return $view;
    }

    /**
     * Creates a key-value where the value is a text-based input field.
     * @param View|string[]|string $key The key (possibly in different languages).
     * @param InputHtmlView $value The value (as an input view).
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createTextField(View|array|string $key, InputHtmlView $value,
                                           bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                           array|string $classes = []) : KeyValueHtmlView {
        if ($value->type() === 'checkbox')
            return self::createCheckbox($key, $value, $frame, $keySize, $valueSize, $classes);
        $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes, $value->getId());
        $view->valueView->add($value);
        return $view;
    }

    /**
     * Creates a key-value where the value is a checkbox.
     * @param View|string[]|string $key The key (possibly in different languages).
     * @param InputHtmlView $value The value (as an input view).
     * @param bool $swap For checkboxes, key and value are exchanged by default.
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createCheckbox(View|array|string $key, InputHtmlView $value, bool $swap = true,
                                          bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                          array|string $classes = []) : KeyValueHtmlView {
        if ($value->type() !== 'checkbox')
            return self::createTextField($key, $value, $frame, $keySize, $valueSize, $classes);
        if ($swap) {
            $tmp = $valueSize;
            $valueSize = $keySize;
            $keySize = $tmp;
            $view = new KeyValueHtmlView($key, $frame, $valueSize, $keySize, $classes, $value->getId());
            $view->valueView->add($value);
            $view->subviews = [$view->subviews[1], $view->subviews[0]];
        } else {
            $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes, $value->getId());
            $view->valueView->add($value);
        }
        return $view;
    }

    /**
     * Creates a key-value where the value is a select (drop down).
     * @param View|string[]|string $key The key (possibly in different languages).
     * @param SelectHtmlView $value The value (as a select).
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createSelect(View|array|string $key, SelectHtmlView $value,
                                        bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                        array|string $classes = []) : KeyValueHtmlView {
        $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes, $value->getId());
        $view->valueView->add($value);
        return $view;
    }

    /**
     * Creates a key-value where the value is a switch.
     * @param View|string[]|string $key The key (possibly in different languages).
     * @param SwitchHtmlView $value The value (as an input view).
     * @param bool $swap For switches, key and value are exchanged by default.
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createSwitch(View|array|string $key, SwitchHtmlView $value, bool $swap = true,
                                        bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                        array|string $classes = []) : KeyValueHtmlView {
        if ($swap) {
            $view = new KeyValueHtmlView($key, $frame, $valueSize, $keySize, $classes, $value->getId());
            $view->valueView->add($value);
            $view->subviews = [$view->subviews[1], $view->subviews[0]];
        } else {
            $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes, $value->getId());
            $view->valueView->add($value);
        }
        return $view;
    }

    /**
     * Place the entries vertically.
     * @return $this
     */
    public function vertical() : static {
        $this->classes[] = 'ver';
        return $this;
    }

    /**
     * Removes the padding.
     * @return $this
     */
    public function noPadding() : static {
        $this->classes[] = 'np';
        return $this;
    }

    /**
     * Get the value view.
     * @return HtmlView
     */
    public function value() : HtmlView {
        return $this->valueView;
    }
}
?>