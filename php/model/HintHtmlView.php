<?php

/**
 * Class HintHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HintHtmlView extends HtmlView {

    /**
     * SystemTaskHtmlView constructor.
     * @param array|string $classes An array of additional classes.
     */
    public function __construct($classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['hints'], $classes));

        $this->js('$("' . $this->idc() . '").on("' . Event::SHOW_HINT . '", function(event, el, content, hint) {' .
            'let c = $(\'' . HintHtmlView::createHint()->generateSafe() . '\');' .
            '$(c).append(content);' .
            '$(c).attr("hint", hint);' .
            '$(this).append(c);' .
            'event.target = el;' .
            'ms($(this), event);' .
            '});'
        );

        $this->js('$("' . $this->idc() . '").on("' . Event::HIDE_HINT . '", function(event, hint) {' .
            '$(this).find("[hint=" + hint + "]").remove();' .
            '});'
        );

        $this->style(Tools::css('.hints', '',
            'visibility: visible', 'opacity: 1', 'position: absolute',
            'transition: visibility 0s, opacity 0.5s linear', 'z-index: 10000000'));
        $this->style(Tools::css('.hints', ':empty', 'width: 0', 'height: 0',
            'visibility: hidden', 'opacity: 0'));

        $this->style(Tools::css('.hints', [' > ', '.hint'], 'width: 250px', 'min-height: 40px',
            'background: var(--hint-background-2)', 'width: 100%',
            'padding: 10px', 'font-size: 12px', 'font-style: normal', 'text-transform: none'));
        $this->style(Tools::css('.hints', [' > ', '.hint', ' > ', 'ul'], 'list-style-type: none',
            'padding-left: 10px'));
    }

    /**
     * Create a new hint.
     * @param HintHtmlView|null $view A potential view.
     * @return HtmlView
     */
    public static function createHint(HintHtmlView $view = null): HtmlView {
        $item = new HtmlView('hint');
        if ($view !== null) $view->addHint('hint');

        return $item;
    }
}
?>