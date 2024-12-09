<?php

/**
 * Class MiniTextButtonHtmlView.
 *
 * @package webship
 * @subpackge php/model/buttons
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class MiniTextButtonHtmlView extends ButtonHtmlView {

    /**
     * Constructor.
     * @param string|null $id The id.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string|null $id, array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'mini';
        parent::__construct($id, 'button', $classes);

        $this->style(Tools::css('button.mini', [],'border: none', 'background: none',
            'height: 20px', 'border-radius: 3px', 'padding: 3px', 'width: initial', 'font-size: 10px',
            'color: var(--font-info)', 'transition: all 0.25s', 'cursor: pointer'));
        $this->style(Tools::css('button.mini', [':hover'], 'background: var(--font-info)',
            'color: var(--neutral)'));
        $this->style(Tools::css('button.mini.del', [':hover'],'background: var(--delete-2-hover-2)',
            'color: var(--neutral)'));
    }

    /**
     * Creates a new mini text button.
     * @param string|null $id The id.
     * @param string|string[] $texts The button texts.
     * @param string[]|string $classes Some classes.
     * @return MiniTextButtonHtmlView
     */
    public static function create(string|null $id, string|array $texts = [],
                                  array|string $classes = []) : MiniTextButtonHtmlView {
        $view = new MiniTextButtonHtmlView($id, $classes);
        if (!is_array($texts)) $texts = [ self::getLanguage() => $texts ];
        foreach ($texts as $l => $text) $view->texts()->text($l, $text);
        return $view;
    }

    /**
     * Creates a delete text button.
     * @param string|null $id The id.
     * @param string|string[] $texts The button texts.
     * @param string[]|string $classes Some classes.
     * @return MiniTextButtonHtmlView
     */
    public static function delete(string|null $id, string|array $texts = [],
                                  array|string $classes = []) : MiniTextButtonHtmlView {
        $view = self::create($id, $texts, $classes);
        $view->addClass('del');
        return $view;
    }
}
?>