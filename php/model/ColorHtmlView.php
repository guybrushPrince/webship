<?php

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

        if ($color === 'none') $font = '#000000';
        else $font = Tools::getFontColor($color);
        $this->localStyle([
            '--bg: ' . $color,
            '--fc: ' . $font,
            '--dc: ' . $color
        ]);

        if ($title) $this->notices([self::getLanguage() => $title]);

        $this->style(Tools::css('.color-v', [], 'background: var(--bg) !important',
            'color: var(--fc)', '--dev-color: var(--dc)'));
    }

    /**
     * Create a color view.
     * @param string $color The color.
     * @param string[]|string $classes Some classes.
     * @param string|null $title An optional title.
     * @return ColorHtmlView
     */
    public static function create(string $color, ?string $title = null, array|string $classes = []) : ColorHtmlView {
        return new ColorHtmlView($color, $classes, $title);
    }
}
?>