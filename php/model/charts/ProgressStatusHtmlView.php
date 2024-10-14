<?php

/**
 * Class ProgressStatusHtmlView.
 *
 *
 */
class ProgressStatusHtmlView extends HtmlView {

    private function __construct(array $states, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'state-bar';
        parent::__construct($classes);

        $this->localStyle('--nr-states: ' . count($states));
        $this->style(Tools::css('.state-bar', [], 'display: grid', 'width: 100%', 'height: 20px',
            '--cell-height: 30px', '--cell-width: 30px', '--cell-gap: 1px',
            'grid-template-columns: repeat(var(--nr-states), 1fr)', 'grid-template-rows: 1fr',
            'column-gap: var(--cell-gap)', 'row-gap: var(--cell-gap)',
            'background: var(--system-background)',
            'border: 1px solid var(--system-background)'));
        $this->style(Tools::css('.state-bar', [' > ', 'div'], 'background: var(--neutral)'));
    }

    public static function create(array $states, int|string $state, ?array $stateInfos = null,
                                  string|array $classes = []) : ProgressStatusHtmlView {
        $view = new ProgressStatusHtmlView($states, $classes);

        foreach ($states as $s) {
            $cell = new HtmlView();
            $view->add($cell);
            if ($stateInfos && array_key_exists($s, $stateInfos)) {
                $info = $stateInfos[$s];
                if (is_array($info)) $info = $info[self::getLanguage()];
            } else $info = $s;
            $cell->notice(self::getLanguage(), $info);
            if ($s === $state) break;
        }

        return $view;
    }

}
?>