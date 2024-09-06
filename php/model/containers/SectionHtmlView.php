<?php

/**
 * Class SectionHtmlView.
 *
 * @package webship
 * @subpackge php/model/containers
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class SectionHtmlView extends HtmlView {

    /**
     * The section / title.
     * @var HtmlView
     */
    private HtmlView $section;

    /**
     * The box / content.
     * @var HtmlView
     */
    private HtmlView $box;

    /**
     * Constructor.
     * @param string|string[] $section The text(s) of the sections.
     * @param bool $open Whether the section is opened or closed.
     * @param string|string[] $classes Some classes.
     */
    private function __construct(string|array $section, bool $open = false, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'sect';
        parent::__construct($classes);

        $this->section = new HtmlView('section');
        $this->add($this->section);
        if (!is_array($section)) $section = [ self::getLanguage() => $section ];
        foreach ($section as $l => $s) $this->section->texts()->text($l, $s);

        $this->box = new HtmlView();
        $this->add($this->box);
        $this->box->ref('foldable');
        if (!$open) $this->box->addClass('closed');

        $this->section->protocol(Event::CLICK)->
            doIt('ToggleClass', ['view' => $this->box, 'class' => 'closed'], 'basics');

        $this->style(Tools::css('.sect', [],'width: 100%', 'display: flex',
            'flex-direction: column', 'padding: 0 0 16px 0'));
        $this->style(Tools::css('.sect', [' > ', '.closed'],'display: none !important'));
        $this->style(Tools::css('.sect', [' > ', '.section'], 'font-family: Roboto, sans-serif',
            'text-transform: none', 'font-size: 1.25em', 'font-weight: lighter', 'width: 100%',
            'cursor: pointer', 'width: 100%'));
        $this->style(Tools::css('.sect', [' > ', '.section', ':after'], 'content: ""',
            'margin-left: 5px', 'background-repeat: no-repeat', 'width: 10px', 'height: 10px', 'background-size: cover',
            'background-image: url("' . HtmlActionProtocol::$serverURL . '/webship/pic/arrow-down-sign-to-navigate.svg")',
            'box-sizing: border-box', 'display: inline-block', 'opacity: 0.25', 'transition: opacity 0.5s'));
        $this->style(Tools::css('.sect', [' > ', '.section', ':hover', ':after'],
            'opacity: 1'));
        $this->style(Tools::css('.sect', [' > ', 'div:nth-child(2)'],'width: 100%',
            'padding: 10px 20px', 'box-sizing: border-box'));
    }

    /**
     * Creates a new section view.
     * @param string|string[] $section The text(s) of the sections.
     * @param bool $open Whether the section is opened or closed.
     * @param string|string[] $classes Some classes.
     * @return SectionHtmlView
     */
    public static function create(string|array $section, bool $open = false,
                                  string|array $classes = []) : SectionHtmlView {
        return new SectionHtmlView($section, $open, $classes);
    }

    /**
     * Get the box.
     * @param string|string[] $classes Some additional classes.
     * @return HtmlView
     */
    public function box(string|array $classes = []) : HtmlView {
        $this->box->addClass($classes);
        return $this->box;
    }

}
?>