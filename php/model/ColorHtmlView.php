<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ColorHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ColorHtmlView extends HtmlView {

    /**
     * ColorHtmlView constructor.
     * @param string $color The color.
     * @param string[]|string $classes Some classes.
     * @param string|null $title An optional title.
     */
    public function __construct(string $color, array|string $classes = [], ?string $title = null) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'color-v';
        parent::__construct($classes);

        $font = Tools::getFontColor($color);
        $this->localStyle([
            '--bg: ' . $color,
            '--fc: ' . $font,
            '--dc: ' . $color
        ]);

        if ($title) $this->notices([self::getLanguage() => $title]);

        $this->style(Tools::css('.color-v', [], 'background: var(--bg) !important',
            'color: var(--fc)', '--dev-color: var(--dc)'));
    }
}
?>