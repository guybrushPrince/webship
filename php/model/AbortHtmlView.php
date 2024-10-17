<?php

/**
 * Class AbortHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
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

        $this->style(Tools::css('button.std-button.reset', '',
            'background: var(--button-abort)'));
        $this->style(Tools::css('button.std-button.reset', ':hover',
            'background: var(--dark-system-background)'));
    }
}
?>