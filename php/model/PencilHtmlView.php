<?php

/**
 * Class PencilHtmlView.
 *
 * @package webship
 * @subpackage php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 */
class PencilHtmlView extends StandardButtonHtmlView {

    /**
     * PencilHtmlView constructor.
     * @param string|string[] $classes Some classes.
     */
    public function __construct(string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct('pencil', array_merge([ 'pencil' ], $classes));

        $this->onlyImg(true);

        $this->style(Tools::css('button.std-button.pencil', [], 'color: var(--font-info)'));
        $this->style(Tools::css('button.std-button.pencil', [':after'], 'content: "\270E"',
            'width: 100%', 'text-align: center', 'font-size: 14px', 'line-height: 15px'));

        $this->style(Tools::css('button.std-button.pencil', ':hover',
            'color: var(--neutral)'));
    }

    /**
     * Return a new pencil html view.
     * @param string[]|string $classes Classes.
     * @return PencilHtmlView
     */
    public static function _new(string|array $classes = []) : PencilHtmlView {
        return new PencilHtmlView($classes);
    }
}
?>