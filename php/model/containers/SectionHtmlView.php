<?php

/**
 * View SectionHtmlView.
 *
 * <p>A view with a title that is called a <em>section</em>. This section is a button at the same time allowing
 * to open or close a content belonging to the section. This content is called the <em>box</em>.</p>
 * <img src="http://localhost/coast/webship/modeldoc/section.png" />
 *
 * <code>
 * SectionHtmlView::create('Hello World!', true)->box()->add(AreaHtmlView::create());
 * </code>
 *
 * @package webship
 * @subpackge php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     SectionHtmlView::create('Hello World!', true)->box()->add(AreaHtmlView::create());
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
        $this->ref('foldable');
        if (!$open) $this->addClass('closed');

        $this->section->protocol(Event::CLICK)->
            doIt('ToggleClass', ['view' => $this, 'class' => 'closed'], 'basics');
        if (!$open) {
            $this->js('(function() { let open = false; $("' . $this->section->idc() . '").on("click", function() { if (!open) $("' . $this->idc() . '").trigger("' . Event::OPEN_LIST . '"); open = true; }); })();');
        } else $this->js('(function() { let open = true; $("' . $this->section->idc() . '").on("click", function() { if (open) $("' . $this->idc() . '").trigger("' . Event::CLOSE_LIST . '"); open = false; }); })();');

        self::addCSS();
    }

    /**
     * Add the CSS styles.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.sect', [],'width: 100%', 'display: flex',
            'flex-direction: column', 'padding: 0 0 16px 0'));
        self::addStyle(Tools::css('.sect.compatible', [],'padding: 16px 0 0 0'));

        self::addStyle(Tools::css('.sect.fit', [],'width: -moz-fit-content', 'width: fit-content',
            'padding-right: 20px'));
        self::addStyle(Tools::css('.sect:last-child', [],'padding: 0'));
        self::addStyle(Tools::css('.sect.fit:last-child', [],'padding: 0'));
        self::addStyle(Tools::css('.sect.closed', [' > ', 'div:not(.section)'],'display: none !important'));
        self::addStyle(Tools::css('.sect.fit:not(.closed)', [],'width: 100%'));
        self::addStyle(Tools::css('.sect', [' > ', '.section'], 'font-family: Roboto, sans-serif',
            'text-transform: none', 'font-size: 1.25em', 'font-weight: lighter', 'width: 100%',
            'cursor: pointer'));
        self::addStyle(Tools::css('.sect', [' > ', '.section', ':after'], 'content: ""',
            'margin-left: 5px', 'background-repeat: no-repeat', 'width: 10px', 'height: 10px', 'background-size: cover',
            'background-image: url(' . HtmlActionProtocol::$serverURL . '/webship/pic/arrow-down-sign-to-navigate.svg)',
            'box-sizing: border-box', 'display: inline-block', 'opacity: 0.25', 'transition: opacity 0.5s'));
        self::addStyle(Tools::css('.sect', [' > ', '.section', ':hover', ':after'],
            'opacity: 1'));
        self::addStyle(Tools::css('.sect', [' > ', 'div:nth-child(2)'],'width: 100%',
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
        if (is_array($classes) && !Tools::isEmpty($classes) || is_string($classes)) $this->box->addClass($classes);
        return $this->box;
    }

    /**
     * Get the section (to extend the click event).
     * @return HtmlView
     */
    public function section() : HtmlView {
        return $this->section;
    }

    /**
     * Fit the section to its size in closed state.
     * @return $this
     */
    public function fit() : static {
        $this->classes[] = 'fit';
        return $this;
    }

}
?>