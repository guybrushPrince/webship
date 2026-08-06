<?php

namespace webship\charts;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View CSSColorGradientHtmlView.
 *
 * <p>Displays a rectangle with a color gradient and a line to mark a percentage on the gradient.
 * When creating the view you can choose the width, the two colors and the percentage.</p>
 *
 * <code>
 * CSSColorGradientHtmlView::create('red', 'green', 75.00, '100px');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/colorgradient.png" />
 *
 * @name CSSColorGradientHtmlView
 * @category charts
 * @package webship
 * @subpackge php/model/charts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz, Franziska Lüder
 * @version 1.1.0
 *
 * @view
 * @examples
 *     CSSColorGradientHtmlView::create('red', 'green', 75.00, '100px');
 */
class CSSColorGradientHtmlView extends HtmlView {

    /**
     * The div-specific style (to avoid a couple of new classes).
     * @var string
     */
    private string $style;

    /**
     * The current state of the gradient.
     * @var string|float|int
     */
    private string|float|int $state;

    /**
     * Constructor.
     * @param string $fromColor The color where it starts.
     * @param string $toColor The color where it ends.
     * @param float|string|int $state The current state within the gradient (between 0-100,00).
     * @param string|null $width The desired width (absolute or relative).
     * @param string|string[] $classes Some classes.
     */
    protected function __construct(string $fromColor, string $toColor, float|string|int $state, ?string $width = null,
                                   string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'css-gradient';
        parent::__construct($classes);
        self::addCSS();
        $this->localStyle('background: linear-gradient(90deg, ' . $fromColor . ' 0%, ' . $toColor . ' 100%);' .
            'width: ' . ($width ? $width : '100%'));
        $this->state = $state;
    }

    /**
     * @inheritDoc
     */
    public static function addCSS(): void {
        self::addStyle(WebshipTools::css('.css-gradient', [], 'height: 20px', 'box-sizing: border-box',
            'border-radius: 3px', 'border: 1px solid var(--ws-std-bg-dark)', 'position: relative'));
        self::addStyle(WebshipTools::css('.css-gradient', [' > ', 'div'], 'height: 20px',
            'box-sizing: border-box', 'width: 2px', 'background: var(--ws-main-font)',
            'position: absolute', 'top: -1px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag(): string {
        $tag = parent::generateOpeningTag();
        $tag .= '<div style="left: ' . $this->state . '%" title="' . $this->state . '%"></div>';
        return $tag;
    }

    /**
     * Creates a css color gradient.
     * @param string $fromColor The color where it starts.
     * @param string $toColor The color where it ends.
     * @param float|string|int $state The current state within the gradient (between 0-100,00).
     * @param string|null $width The desired width (absolute or relative).
     * @param string|string[] $classes Some classes.
     * @return CSSColorGradientHtmlView
     */
    public static function create(string $fromColor, string $toColor, float|string|int $state, string $width = null,
                                  string|array $classes = []): CSSColorGradientHtmlView {
        return new CSSColorGradientHtmlView($fromColor, $toColor, $state, $width, $classes);
    }

}
?>