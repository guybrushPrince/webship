<?php

/**
 * Class OpeningCardHtmlView.
 *
 * @package webship
 * @subpackge php/model/cards
 *
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
     * @param int $valueSize The size of the value field.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $key, bool $frame = true, int $keySize = 30, int $valueSize = 70,
                                 array|string $classes = [], ?string $for = null) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'kv';
        if ($frame) $classes[] = 'frame';
        parent::__construct($classes);

        $keyRatio = round($keySize / ($keySize + $valueSize), 2) * 100;
        $valueRatio = round($valueSize / ($keySize + $valueSize), 2) * 100;

        if (!$for) $keyView = $this->add(new HtmlView(['k', 'd' . $keyRatio]));
        else $keyView = $this->add(new FormLabelHtmlView($for, ['k', 'd' . $keyRatio]));
        if (!is_array($key)) $key = [self::getLanguage() => $key];
        foreach ($key as $l => $k) $keyView->texts()->text($l, $k);

        $this->valueView = new HtmlView(['v', 'd' . $valueRatio]);
        $this->add($this->valueView);

        $this->style(Tools::css('.kv', [], 'display: flex', 'flex-direction: row', 'flex-wrap: nowrap',
            'width: 100%', 'align-items: center', 'padding: 10px', 'box-sizing: border-box'));
        $this->style(Tools::css('.kv.frame', [], 'border: 1px solid var(--ultra-light-system-background)'));
        $this->style(Tools::css('.kv', [' > ', '.k'], 'display: flex', 'flex-direction: row',
            'align-items: center', 'justify-content: flex-start', 'margin: 0'));
        $this->style(Tools::css('.kv', [' > ', '.v'], 'display: flex', 'flex-direction: row',
            'align-items: center', 'justify-content: flex-start', 'margin: 0'));
        $this->style(Tools::css('.kv', [' > ', '.v', ' > ', 'input[type="checkbox"]'], 'width: 25px'));
        $this->style(Tools::css('.kv', [' > ', '.d' . $keyRatio], 'width: ' . $keyRatio . '%'));
        $this->style(Tools::css('.kv', [' > ', '.d' . $valueRatio], 'width: ' . $valueRatio . '%'));
        $this->style(Tools::css('.kv.frame', [' + ', '.kv.frame'], 'border-top: none'));
    }

    /**
     * Creates a key-value where the value is a string.
     * @param string[]|string $key The key (possibly in different languages).
     * @param string $value The value (as string).
     * @param bool $frame Whether there shall be a frame or not.
     * @param int $keySize The size of the key.
     * @param int $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createString(array|string $key, string $value,
                                        bool $frame = true, int $keySize = 30, int $valueSize = 70,
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
     * @param int $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createView(array|string $key, HtmlView $value,
                                      bool $frame = true, int $keySize = 30, int $valueSize = 70,
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
     * @param int $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createTextField(array|string $key, InputHtmlView $value,
                                           bool $frame = true, int $keySize = 30, int $valueSize = 70,
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
     * @param int $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createCheckbox(array|string $key, InputHtmlView $value, bool $swap = true,
                                           bool $frame = true, int $keySize = 30, int $valueSize = 70,
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
     * @param int $valueSize The size of the value.
     * @param string[]|string $classes Some classes.
     * @return KeyValueHtmlView
     */
    public static function createSelect(array|string $key, SelectHtmlView $value,
                                        bool $frame = true, int $keySize = 30, int $valueSize = 70,
                                        array|string $classes = []) : KeyValueHtmlView {
        $view = new KeyValueHtmlView($key, $frame, $keySize, $valueSize, $classes, $value->getId());
        $view->valueView->add($value);
        return $view;
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