<?php

namespace webship\charts;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View ProgressStatusHtmlView.
 *
 * <p>Displays a row of squares that represent the progress of a task. Each square represents one status.</p>
 *
 * <code>
 * ProgressStatusHtmlView::create(["not yet worked on", "in progress", "done"], 'done', [], 'red');
 * </code>
 * <img src="http://localhost/coast/webship/modeldoc/progressred.png" />
 *
 * <code>
 * ProgressStatusHtmlView::create(["not yet worked on", "in progress", "done"], 'done');
 * </code>
 * <img src="http://localhost/coast/webship/modeldoc/progress.png" />
 *
 * @package webship
 * @subpackge php/model/charts
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz, Franziska Lüder
 * @version 1.1.0
 *
 * @view
 * @examples
 *     ProgressStatusHtmlView::create(["not yet worked on", "in progress", "done"], 'done', [], 'red');
 *     ProgressStatusHtmlView::create(["not yet worked on", "in progress", "done"], 'done');
 */
class ProgressStatusHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param int[]|string[] $states An array of possible states.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array $states, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'state-bar';
        parent::__construct($classes);
        self::addCSS();
        $this->localStyle('--ws-nr-states: ' . count($states));
        $this->localStyle('--ws-state-size: 10px');
        $this->localStyle('--ws-border-size: 1px');
    }

    /**
     * @inheritDoc
     */
    public static function addCSS(): void {
        self::addStyle(WebshipTools::css('.state-bar', [], 'display: grid', 'width: calc(var(--ws-nr-states) * var(--ws-state-size) + 2 * var(--ws-border-size))',
            'height: calc(var(--ws-state-size)+2*var(--ws-border-size))',
            '--ws-pg-cell-gap: 1px', 'grid-template-columns: repeat(var(--ws-nr-states), 1fr)', 'grid-template-rows: 1fr',
            'column-gap: var(--ws-pg-cell-gap)', 'row-gap: var(--ws-pg-cell-gap)',
            '--ws-pg-background: var(--ws-neutral)',
            'background: var(--ws-std-bg)',
            'border: var(--ws-border-size) solid var(--ws-std-bg)'));
        self::addStyle(WebshipTools::css('.state-bar', [' > ', 'div'], 'background: var(--ws-pg-background)',
            'aspect-ratio: 1'));
        self::addStyle(WebshipTools::css('.state-bar', [' > ', 'div.undone'],
            'background: var(--ws-std-bg)'));
    }

    /**
     * Create a progress status.
     * @param int[]|string[] $states An array of possible states.
     * @param int|string $state The current state.
     * @param string[]|null|string[][] $stateInfos About each state some information to note.
     * @param string|null $backgroundColor A
     * @param string|string[] $classes Some classes.
     * @return ProgressStatusHtmlView
     */
    public static function create(array $states, int|string $state, ?array $stateInfos = null, ?string $backgroundColor = null,
                                  string|array $classes = []): ProgressStatusHtmlView {
        $view = new ProgressStatusHtmlView($states, $classes);

        $undone = false;
        foreach ($states as $s) {
            $cell = new HtmlView();
            $view->add($cell);
            if (!$undone) {
                if ($stateInfos && array_key_exists($s, $stateInfos)) {
                    $info = $stateInfos[$s];
                    if (is_array($info)) $info = $info[self::getLanguage()];
                } else $info = $s;
                $cell->notice(self::getLanguage(), $info);
                if ($s === $state) $undone = true;
            } else {
                $cell->addClass('undone');
            }
        }
        if (!is_null($backgroundColor)) {
            $view->localStyle('--ws-pg-background: ' . $backgroundColor);
        }

        return $view;
    }

}
?>