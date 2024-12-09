<?php

/**
 * Class SectionLikeButtonHtmlView.
 *
 * @package webship
 * @subpackage php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class SectionLikeButtonHtmlView extends ButtonHtmlView {

    /**
     * Constructor.
     * @param string|null $id The id of the button.
     * @param string|null $img A small image to display behind the text.
     * @param string[]|string $classes Some classes.
     * @param string|null $type The button type.
     */
    public function __construct(?string $id = null, ?string $img = null, array|string $classes = [],
                                ?string $type = 'button') {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'sect-but';
        parent::__construct($id, $type, $classes);

        if ($img) $this->localStyle('--after-image: url("' . HtmlActionProtocol::$serverURL . $img . '")');

        $this->style(Tools::css('button.sect-but', [],'width: 100%', 'display: flex',
            'flex-direction: row', 'padding: 0 0 16px 0', 'border: none', 'background: none', 'height: auto',
            'font-family: Roboto, sans-serif', 'text-transform: none', 'font-size: 1.25em', 'font-weight: lighter',
            'cursor: pointer'));
        $this->style(Tools::css('button.sect-but:last-child', [],'padding: 0'));
        $this->style(Tools::css('button.sect-but', [':after'], 'content: ""',
            'margin-left: 5px', 'background-repeat: no-repeat', 'width: 15px', 'height: 15px', 'background-size: cover',
            'background-image: var(--after-image)',
            'box-sizing: border-box', 'display: inline-block', 'opacity: 0.25', 'transition: opacity 0.5s'));
        $this->style(Tools::css('button.sect-but', [':hover', ':after'], 'opacity: 1'));
    }
}
?>