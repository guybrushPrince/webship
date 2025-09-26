<?php

/**
 * View StandardHtmlView.
 *
 * <p>A simple HTML view that has the full width of available space (in its surrounding environment), has
 * a standard padding of 20px, is a border-box, and provides a relative position
 * (e.g., for {@link ControlPanelHtmlView}).</p>
 *
 * <p><em>Note:</em> Although this part can be used alone, it is usually used in a specialized class.</p>
 *
 * @package webship
 * @subpackge php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 */
class StandardHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes.
     */
    public function __construct(string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
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
    }
}
?>