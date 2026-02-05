<?php

/**
 * View AbortHtmlView.
 *
 * <p>Displays an abort button which is red by default.</p>
 *
 * <code>
 * $abortButton = new AbortHtmlView();
 * $abortButton->texts('abort');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/abortbutton.png" />
 *
 * @package webship
 * @subpackge php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz, Franziska Lüder
 * @version 1.2.0
 *
 * @example
 * $abortButton = new AbortHtmlView();
 * $abortButton->texts('abort');
 */
class AbortHtmlView extends StandardButtonHtmlView {

    /**
     * AbortHtmlView constructor.
     * @param string|int $id The id.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(string|int $id, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct($id, array_merge(['reset'], $classes), 'reset');
        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('button.std-button.reset', '',
            'background: var(--button-abort)'));
        self::addStyle(Tools::css('button.std-button.reset', ':hover',
            'background: var(--dark-system-background)'));
    }

    /**
     * Create a new abort button.
     * @inheritDoc
     * @param string|null $id The id of the button.
     * @param string|array $classes Some classes.
     * @return AbortHtmlView
     */
    public static function create(?string $id = null, string|array $classes = []): AbortHtmlView {
        return new AbortHtmlView($id, $classes);
    }
}
?>