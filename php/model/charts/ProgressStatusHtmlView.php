<?php

/**
 * Class ProgressStatusHtmlView.
 *
 *
 */
class ProgressStatusHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param int[]|string[] $states An array of possible states.
     * @param string|string[] $classes Some classes.
     */
    private function __construct(array $states, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'state-bar';
        parent::__construct($classes);

        $this->localStyle('--nr-states: ' . count($states));
        $this->style(Tools::css('.state-bar', [], 'display: grid', 'width: 100%', 'height: 10px',
            '--cell-gap: 1px', 'grid-template-columns: repeat(var(--nr-states), 1fr)', 'grid-template-rows: 1fr',
            'column-gap: var(--cell-gap)', 'row-gap: var(--cell-gap)',
            'background: var(--system-background)',
            'border: 1px solid var(--system-background)'));
        $this->style(Tools::css('.state-bar', [' > ', 'div'], 'background: var(--neutral)',
            'aspect-ratio: 1'));
        $this->style(Tools::css('.state-bar', [' > ', 'div.undone'],
            'background: var(--system-background)'));
    }

    /**
     * Create a progress status.
     * @param int[]|string[] $states An array of possible states.
     * @param int|string $state The current state.
     * @param string[]|null|string[][] $stateInfos About each state some information to note.
     * @param string|string[] $classes Some classes.
     * @return ProgressStatusHtmlView
     */
    public static function create(array $states, int|string $state, ?array $stateInfos = null,
                                  string|array $classes = []) : ProgressStatusHtmlView {
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

        return $view;
    }

}
?>