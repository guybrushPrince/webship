<?php

namespace webship\containers;

use HtmlView;
use Tools;

/**
 * View StandardHtmlView.
 *
 * <p>A simple HTML view that has the full width of available space (in its surrounding environment), has
 * a standard padding of 20px, is a border-box, and provides a relative position
 * (e.g., for {@link ControlPanelHtmlView}).</p>
 *
 * <p><em>Note:</em> Although this part can be used alone, it is usually used in a specialized class.</p>
 *
 * <code>
 * StandardHtmlView::createStandard();
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/standard.png" />
 *
 * @package webship
 * @subpackge php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     StandardHtmlView::createStandard();
 */
class StandardHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'std';
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * Add CSS styles.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.std', [], 'width: 100%', 'padding: 20px 20px',
            'box-sizing: border-box', 'position: relative'));
        self::addStyle(Tools::css('.std', ['.p0'], 'padding: 0'));
        self::addStyle(Tools::css('.std', ['.p5'], 'padding: 5px'));
        self::addStyle(Tools::css('.std', ['.p10'], 'padding: 10px'));
        self::addStyle(Tools::css('.std', ['.p15'], 'padding: 15px'));
    }

    /**
     * Create a standard html view.
     * @param string[]|string $classes Some classes.
     * @return StandardHtmlView
     */
    public static function createStandard(array|string $classes = []) : StandardHtmlView {
        return new StandardHtmlView($classes);
    }
}
?>