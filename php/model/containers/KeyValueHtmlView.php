<?php

/**
 * Class KeyValueHtmlView.
 *
 * @package webship
 * @subpackge php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class KeyValueHtmlView extends HtmlView {

    /**
     * The value view.
     * @var HtmlView
     */
    private HtmlView $valueView;

    /**
     * Constructor.
     * @param string|string[] $key The key.
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of key field.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @param string|null $for An attribute for.
     * @param bool $forceKeySize Whether empty spaces shall be replaced.
     * @param string|string[]|null $afterText Some texts (as units) to present after the input.
     */
    protected function __construct(array|string $key, bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                   array|string $classes = [], ?string $for = null, bool $forceKeySize = true,
                                   string|array|null $afterText = null) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'kv';
        if ($frame) $classes[] = 'frame';
        parent::__construct($classes);

        if ($valueSize === null) $valueSize = 100 - $keySize;
        $keyRatio = round($keySize / ($keySize + $valueSize), 2) * 100;
        $valueRatio = round($valueSize / ($keySize + $valueSize), 2) * 100;

        if ($afterText) $valueRatio -= 5;

        if (!$for) $keyView = $this->add(new HtmlView(['k', 'd' . $keyRatio]));
        else $keyView = $this->add(new FormLabelHtmlView($for, ['k', 'd' . $keyRatio]));
        if (!is_array($key)) $key = [self::getLanguage() => $key];
        foreach ($key as $l => $k) $keyView->texts()->text($l, $forceKeySize ? str_replace(' ', '&nbsp;', $k) : $k);

        $this->valueView = new HtmlView(['v', 'd' . $valueRatio]);
        $this->add($this->valueView);

        if ($afterText) {
            if (!is_array($afterText)) $afterText = [ self::getLanguage() => $afterText ];
            $afterView = $this->add(new HtmlView('ka'));
            foreach ($afterText as $l => $t) $afterView->texts()->text($l, $t);
        }

        $this->style(Tools::css('.kv', [], 'display: flex', 'flex-direction: row', 'flex-wrap: nowrap',
            'width: 100%', 'align-items: center', 'padding: 10px', 'box-sizing: border-box', 'gap: 10px'));
        $this->style(Tools::css('.kv.frame', [], 'border: 1px solid var(--ultra-light-system-background)'));
        $this->style(Tools::css('.kv', [' > ', '.k'], 'display: flex', 'flex-direction: row',
            'align-items: center', 'justify-content: flex-start', 'margin: 0'));
        $this->style(Tools::css('.kv', [' > ', '.v'], 'display: flex', 'flex-direction: row',
            'align-items: center', 'justify-content: flex-start', 'margin: 0'));
        $this->style(Tools::css('.kv', [' > ', '.v', ' > ', 'input[type="checkbox"]'], 'width: 25px'));
        $this->style(Tools::css('.kv', [' > ', '.d' . $keyRatio], 'width: ' . $keyRatio . '%'));
        $this->style(Tools::css('.kv', [' > ', '.d' . $valueRatio], 'width: ' . $valueRatio . '%'));
        $this->style(Tools::css('.kv', [' > ', '.ka'], 'width: 5%'));
        $this->style(Tools::css('.kv.frame', [' + ', '.kv.frame'], 'border-top: none'));
        $this->style(Tools::css('.kv.ver', [], 'flex-direction: column'));
        $this->style(Tools::css('.kv.ver', [' > ', '*'], 'width: 100%'));
        $this->style(Tools::css('.kv.np', [], 'padding: 0'));
    }

    /**
     * Creates a key-value where the value is a string.
     * @param string[]|string $key The key (possibly in different languages).
     * @param string $value The value (as string).
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createString(array|string $key, string $value,
                                        bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                        array|string $classes = []) : KeyValueHtmlView {
        $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes);
        $view->valueView->texts()->text(self::getLanguage(), $value);
        return $view;
    }

    /**
     * Creates a key-value where the value is a view.
     * @param string[]|string $key The key (possibly in different languages).
     * @param HtmlView $value The value (as view).
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createView(array|string $key, HtmlView $value,
                                      bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                      array|string $classes = []) : KeyValueHtmlView {
        $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes);
        $view->valueView->add($value);
        return $view;
    }

    /**
     * Creates a key-value where the value is a text-based input field.
     * @param string[]|string $key The key (possibly in different languages).
     * @param InputHtmlView $value The value (as an input view).
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createTextField(array|string $key, InputHtmlView $value,
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
     * @param string[]|string $key The key (possibly in different languages).
     * @param InputHtmlView $value The value (as an input view).
     * @param bool $swap For checkboxes, key and value are exchanged by default.
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createCheckbox(array|string $key, InputHtmlView $value, bool $swap = true,
                                           bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                           array|string $classes = []) : KeyValueHtmlView {
        if ($value->type() !== 'checkbox')
            return self::createTextField($key, $value, $frame, $keySize, $valueSize, $classes);
        if ($swap) {
            $view = new KeyValueHtmlView($key, $frame, $valueSize, $keySize, $classes, $value->getId());
            $view->valueView->add($value);
            $view->subviews = [ $view->subviews[1], $view->subviews[0] ];
        } else {
            $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes, $value->getId());
            $view->valueView->add($value);
        }
        return $view;
    }

    /**
     * Creates a key-value where the value is a select (drop down).
     * @param string[]|string $key The key (possibly in different languages).
     * @param SelectHtmlView $value The value (as a select).
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int|null $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createSelect(array|string $key, SelectHtmlView $value,
                                        bool $frame = true, int $keySize = 30, ?int $valueSize = null,
                                        array|string $classes = []) : KeyValueHtmlView {
        $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes, $value->getId());
        $view->valueView->add($value);
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