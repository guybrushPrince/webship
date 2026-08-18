<?php

namespace webship\tabs;

use Exception;
use ship\interoperability\SLanguage as Language;
use ship\views\TextView;
use webship\buttons\CloserHtmlView;
use webship\containers\ContainerItemHtmlView;
use webship\core\HtmlView;
use webship\interoperability\WEvent;
use webship\symbols\ImageHtmlView;
use webship\WebshipTools;

/**
 * View TabContainerItemHtmlView.
 *
 * <p>A single tab of a {@link TabContainerHtmlView}.</p>
 *
 * <img src="http://localhost/coast/webship/modeldoc/tabcontaineritem.png" />
 *
 * @title TabContainerItem 
 * @category tabs
 * 
 * @see TabContainerHtmlView
 * @package webship
 * @subpackage php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.5.0
 *
 * @view
 * @examples
 *     See {@link TabContainerHtmlView}
 */
class TabContainerItemHtmlView extends ContainerItemHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     * @param bool $knownParent If the parent container is known and, therefore, can be triggered directly.
     */
    private function __construct(array|string $classes = [], bool $knownParent = false) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['tab'], $classes));

        // Add texts for the header.
        $this->add(new HtmlView())->add(new HtmlView('icon'))->parent()->texts();

        self::addCSS();

        if (!$knownParent) $this->js('$("' . $this->idc() . '").trigger("' . WEvent::NEW_TAB . '");');
        $this->js('$("' . $this->idc() . ' > .tab-header").on("click ' . WEvent::SWITCH_TAB . '", function(event) { focusTab(this, "' . WEvent::CHANGE_TAB . '"); });');
        $this->js('$("' . $this->idc() . '").on("' . WEvent::SWITCH_TAB . '", function(event) { focusTab($(this).children(".tab-header"), "' . WEvent::CHANGE_TAB . '"); });');
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.tab', [], 'z-index: 0'));

        self::addStyle(WebshipTools::css('.tab', [' > ', '.tab-header'],
            'position: absolute', 'top: -25px', 'height: 25px', 'z-index: 2',
            'background: var(--ws-tab-head-bg)', 'border: solid 2px var(--ws-tab-brd)',
            'border-bottom: none', 'display: flex', 'align-items: center', 'padding: 0 5px',
            'box-sizing: border-box', 'cursor: pointer', 'border-radius: 6px 6px 0 0'));
        self::addStyle(WebshipTools::css('.tabbed:not(.lo)', [' > ', '.tab:first-child', ' > ', '.tab-header'],
            'border-left: solid 2px var(--ws-tab-brd)'));
        self::addStyle(WebshipTools::css('.tab', [' > ', '.tab-header', ' > ', 'div:first-child'],
            'display: flex', 'flex-direction: row', 'flex-wrap: nowrap'));
        self::addStyle(WebshipTools::css('.tab', [' > ', '.tab-header', ' ', '.icon:empty'],
            'display: none'));
        self::addStyle(WebshipTools::css('.tab', [' > ', '.tab-header', ' ', '.icon:not(:empty)'],
            'margin-right: 5px', 'width: 15px', 'height: 15px', 'display: flex',
            'align-items: center', 'justify-content: center', 'border-radius: 50%'));
        self::addStyle(WebshipTools::css('.tab', [' > ', '.tab-header', ' ', '.icon', ' > ', 'img'],
            'height: 11px'));
        self::addStyle(WebshipTools::css('.tab', [' > ', '.tab-header', ' > ', '.closer'],
            'width: 20px', 'height: 25px', 'padding-right: 0'));
        self::addStyle(WebshipTools::css('.tab', [':not(.active)', ' > ', '.tab-header'],
            'background: var(--ws-tab-head-bg-ina)'));
        self::addStyle(WebshipTools::css('.tab', [':not(.active)', ' > ', '.tab-header:hover'],
            'background: var(--ws-tab-head-bg-hvr)'));
        self::addStyle(WebshipTools::css('.tab', ['.active', ' > ', '.tab-header', ' ', '.closer'],
            'text-shadow: none'));
        self::addStyle(WebshipTools::css('.tab', [':first-child', ' > ', '.tab-header', ' > ', ':first-child'],
            'padding-right: 4px'));
        self::addStyle(WebshipTools::css('.tab', [':first-child', ' > ', '.tab-header', ' ', '.closer'],
            'display: none'));

        self::addStyle(WebshipTools::css('.tab', [' > ', '.tab-separator'],
            'position: absolute', 'left: 0', 'top: -2px', 'width: 100%', 'box-sizing: border-box',
            'border-bottom: solid 2px var(--ws-tab-brd)', 'z-index: 1'));

        self::addStyle(WebshipTools::css('.tab', [' > ', '.tab-content'], 'box-sizing: border-box',
            'position: absolute', 'width: 100%', 'top: 0', 'background: var(--ws-tab-bg)',
            'height: calc(100% - 27px)', 'overflow-y: auto'));

        self::addStyle(WebshipTools::css('.tab', [' > ', '.tab-content', ' > ', '.panel'],
            'border: none'));
    }

    /**
     * Create a new tab.
     * @param string[]|string $classes Some classes.
     * @param bool $knownParent If the parent container is known and, therefore, can be triggered directly.
     * @param string|null $img An optional path to an image.
     * @param string|null $type An optional datatype.
     * @return TabContainerItemHtmlView
     */
    public static function create(array|string $classes = [], bool $knownParent = false,
                                  string|null $img = null, string|null $type = null) : TabContainerItemHtmlView {
        $tab = new TabContainerItemHtmlView($classes, $knownParent);
        if (!is_null($img)) $tab->img($img, $type);
        return $tab;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function generateOpeningTag() : string {
        $code = parent::generateOpeningTag();
        if ($this->subviews[0] instanceof HtmlView) {
            $view = array_shift($this->subviews);
            $code .= '<div class="tab-header">' . $view->generateSafe();
            if (count($this->subviews) >= 1 && $this->subviews[0] instanceof CloserHtmlView) {
                $closer = array_shift($this->subviews);
                $code .= $closer->generateSafe() . '';
                $code .= '<style type="text/css">' . $closer->generateStyle() . '</style>';
                $code .= '<script>' . $closer->generateJS() . '</script>';
            }
            $code .= '</div>';
            $code .= '<div class="tab-separator"></div>';
        }
        return $code . '<div class="tab-content">';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</div>' . parent::generateClosingTag();
    }

    /**
     * Set the header of the tab.
     * @param string[]|string|int $language The language or neutral text or language-indexed texts.
     * @param string|null $text The header.
     * @return $this
     */
    public function header(array|string|int $language, string|null $text = null) : static {
        if (is_int($language)) {
            $texts = [ $language => $text ];
        } else if (is_string($language)) {
            $texts = [ self::getLanguage() => $language ];
        } else $texts = $language;
        foreach ($texts as $language => $text) {
            if ($this->subviews[0]->subviews[1] instanceof TextView) {
                $this->subviews[0]->subviews[1]->text($language, $text);
            }
        }
        return $this;
    }

    /**
     * Enables to add an image to the tab.
     * @param string $path The path to the image.
     * @return $this
     */
    public function img(string $path, ?string $type = null) : static {
        $iconView = $this->subviews[0]->subviews[0];
        $iconView->add(new ImageHtmlView($path));
        if ($type) $iconView->data($type);
        return $this;
    }

    /**
     * Generate a closer view.
     * @param string[]|string $classes Some classes.
     * @return CloserHtmlView
     */
    public function closer(string|array $classes = []) : CloserHtmlView {
        $closer = CloserHtmlView::_new($classes);
        $closer->mini()->plain();
        $closer->notice(Language::ENGLISH, 'Close this tab');
        $closer->notice(Language::GERMAN, 'Tab schließen');
        $this->add($closer);
        return $closer;
    }

    /**
     * Sets the focus to this tab.
     * @return $this
     */
    public function focus() : static {
        $this->js('$("' . $this->idc() . ' > .tab-header").trigger("click");');
        return $this;
    }
}
?>