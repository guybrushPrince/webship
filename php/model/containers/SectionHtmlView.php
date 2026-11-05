<?php

namespace webship\containers;

use webship\controller\BasicFunctions;
use webship\interoperability\WEvent;
use webship\core\HtmlView;
use webship\protocols\HtmlActionProtocol;
use webship\WebshipTools;

/**
 * View SectionHtmlView.
 *
 * <p>A view with a title that is called a <em>section</em>. This section is a button at the same time allowing
 * to open or close a content belonging to the section. This content is called the <em>box</em>.</p>
 *
 * <code>
 * SectionHtmlView::create('Hello World!', true)->box()->add(AreaHtmlView::create());
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/section.png" />
 *
 * @title Section
 * @category containers
 * 
 * @package webship
 * @subpackage php/model/containers
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
     * @param string[]|string $section The text(s) of the sections.
     * @param bool $open Whether the section is opened or closed.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $section, bool $open = false, bool $openable = true,
                                 array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        $classes[] = 'sect';
        if (!$openable) {
            $open = true;
            $classes[] = 'ao';
        }
        parent::__construct($classes);

        $this->section = new HtmlView('section');
        $this->add($this->section);
        if (!is_array($section)) $section = [self::getLanguage() => $section];
        $this->section->texts($section);

        $this->box = ContainerHtmlView::createIt();
        $this->add($this->box);
        $this->ref('foldable');
        if (!$open) $this->addClass('closed');

        if ($openable) {
            $this->section->protocol(WEvent::CLICK)->
                doIt(BasicFunctions::ToggleClass, ['view' => $this, 'class' => 'closed'], BasicFunctions::environment());
            if (!$open) {
                $this->js('(function() { let open = false; $("' . $this->section->idc() . '").on("click", function() { if (!open) $("' . $this->idc() . '").trigger("' . WEvent::OPEN_LIST . '"); open = true; }); })();');
            } else $this->js('(function() { let open = true; $("' . $this->section->idc() . '").on("click", function() { if (open) $("' . $this->idc() . '").trigger("' . WEvent::CLOSE_LIST . '"); open = false; }); })();');
        }

        self::addCSS();
    }

    /**
     * Add the CSS styles.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.sect', [], 'width: 100%', 'display: flex',
            'flex-direction: column', 'padding: 0 0 16px 0'));
        self::addStyle(WebshipTools::css('.sect.compatible', [], 'padding: 16px 0 0 0'));
        self::addStyle(WebshipTools::css('.sect.single', [], 'padding: 0'));

        self::addStyle(WebshipTools::css('.sect.fit', [], 'width: -moz-fit-content', 'width: fit-content',
            'padding-right: 20px'));
        self::addStyle(WebshipTools::css('.sect:not(.not-last):last-child', [], 'padding: 0'));
        self::addStyle(WebshipTools::css('.sect.fit:last-child', [], 'padding: 0'));
        self::addStyle(WebshipTools::css('.sect.closed', [' > ', 'div:not(.section)'], 'display: none !important'));
        self::addStyle(WebshipTools::css('.sect.fit:not(.closed)', [], 'width: 100%'));
        self::addStyle(WebshipTools::css('.sect', [' > ', '.section'], 'font-family: Roboto, sans-serif',
            'text-transform: none', 'font-size: 1.25em', 'font-weight: lighter', 'width: 100%',
            'cursor: pointer'));
        self::addStyle(WebshipTools::css('.sect.ao', [' > ', '.section'], 'cursor: auto'));
        self::addStyle(WebshipTools::css('.sect.small', [' > ', '.section'], 'font-size: 1.0em',
            'font-style: italic'));
        self::addStyle(WebshipTools::css('.sect:not(.ao)', [' > ', '.section', ':after'], 'content: ""',
            'margin-left: 5px', 'background-repeat: no-repeat', 'width: 10px', 'height: 10px', 'background-size: cover',
            'background-image: url(' . HtmlActionProtocol::getServerURL() . '/webship/pic/arrow-down.svg)',
            'box-sizing: border-box', 'display: inline-block', 'opacity: 0.25', 'transition: opacity 0.5s'));
        self::addStyle(WebshipTools::css('.sect', [' > ', '.section', ':hover', ':after'],
            'opacity: 1'));
        self::addStyle(WebshipTools::css('.sect', [' > ', 'div:nth-child(2)'], 'width: 100%',
            'padding: 10px 20px', 'box-sizing: border-box'));
        self::addStyle(WebshipTools::css('.sect.small', [' > ', 'div:nth-child(2)'], 'padding: 5px 10px'));
    }

    /**
     * Creates a new section view.
     * @param string[]|string $section The text(s) of the sections.
     * @param bool $open Whether the section is opened or closed.
     * @param bool $openable If the section can be opened and closed.
     * @param string[]|string $classes Some classes.
     * @return SectionHtmlView
     */
    public static function create(array|string $section, bool $open = false, bool $openable = true,
                                  array|string $classes = []) : SectionHtmlView {
        return new SectionHtmlView($section, $open, $openable, $classes);
    }

    /**
     * Get the box.
     * @param string|string[] $classes Some additional classes.
     * @return HtmlView
     */
    public function box(string|array $classes = []) : HtmlView {
        if (is_array($classes) && !WebshipTools::isEmpty($classes) || is_string($classes)) $this->box->addClass($classes);
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

    /**
     * Sets a lower font size.
     * @return $this
     */
    public function small() : static {
        $this->classes[] = 'small';
        return $this;
    }

    /**
     * Make this section singleton (so that it does not have padding).
     * @return $this
     */
    public function single() : static {
        $this->addClass('single');
        return $this;
    }

    /**
     * Setting this section to being not the last.
     * @return $this
     */
    public function notLast() : static {
        $this->addClass('not-last');
        return $this;
    }

}
?>