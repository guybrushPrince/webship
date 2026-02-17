<?php

namespace webship\buttons;

use webship\WebshipTools;

/**
 * View AbortHtmlView.
 *
 * <p>Displays an abort button which is red by default.</p>
 *
 * <code>
 * AbortHtmlView::create('abort-1')->texts('Abort')
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/abort.png" />
 *
 * @package webship
 * @subpackge php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Franziska Lüder
 * @version 1.5.0
 *
 * @view
 * @example
 *     AbortHtmlView::create('abort-1')->texts('Abort')
 */
class AbortHtmlView extends StandardButtonHtmlView {

    /**
     * AbortHtmlView constructor.
     * @param string|int $id The id.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string|int $id, array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct($id, array_merge(['reset'], $classes), 'reset');
        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('button.std-button.reset', '',
            'background: var(--button-abort)'));
        self::addStyle(WebshipTools::css('button.std-button.reset', ':hover',
            'background: var(--dark-system-background)'));
    }

    /**
     * Create a new abort button.
     * @inheritDoc
     * @return AbortHtmlView
     */
    public static function create(?string $id = null, array|string $classes = []) : AbortHtmlView {
        return new AbortHtmlView($id, $classes);
    }
}
?>