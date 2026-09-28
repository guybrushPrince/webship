<?php

namespace webship\tabs;

use webship\interoperability\WEvent;
use webship\containers\ContainerHtmlView;
use webship\WebshipTools;

/**
 * View TabContainerHtmlView.
 *
 * <p>Provides a container with different tabs. Everytime, one tab is focused and in the foreground. All other
 * tabs are in the background.</p>
 * <p>Since a {@link TabContainerHtmlView} is a {@link ContainerHtmlView} it can reuse all its styling
 * opportunities.</p>
 * <p>The tabs are {@link TabContainerItemHtmlView} views.</p>
 * <p>The necessary JavaScript is defined in /js/shiptabs.js.</p>
 *
 * <code>
 * $tabs = TabContainerHtmlView::create(['h100']);
 * $tabs->tab('Hello World');
 * $tabs->closingTab('Hello Thomas');
 * $tabs->closingTab('Hello Survey', [], '/webship/pic/help.svg', 'survey');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/tabcontainer.png" />
 *
 * @title TabContainer
 * @category tabs
 * 
 * @package webship
 * @subpackage php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.5.0
 *
 * @view
 * @examples
 *     $tabs = TabContainerHtmlView::create(['h100']);
 *     $tabs->tab('Hello World');
 *     $tabs->closingTab('Hello Thomas');
 *     $tabs->closingTab('Hello Survey', [], '/webship/pic/help.svg', 'survey');
 */
class TabContainerHtmlView extends ContainerHtmlView {

    /**
     * A default distance of the header.
     */
    public const TAB_HEADER_DISTANCE = 1;

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['tabbed'], $classes), 'var(--ws-tab-main-clr)');

        self::addCSS();

        $this->js('$("' . $this->idc() . '").on("' . WEvent::NEW_TAB . '", function(event) { createNewTab(event, this, ' . self::TAB_HEADER_DISTANCE . '); });');
        $this->js('$("' . $this->idc() . '").on("' . WEvent::CLOSE_TAB . '", function(event) { closeTab(event, this, "' . WEvent::DELETE . '", ' . self::TAB_HEADER_DISTANCE . '); });');
        $this->js('(() => { initTab("' . $this->idc() . '", ' . self::TAB_HEADER_DISTANCE . '); })();');
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.tabbed', [],
            '--ws-tab-hd-hgt: 27px',
            'position: relative',
            'width: 100%', 'height: 100%', 'overflow-y: clip',
            'padding-top: var(--ws-tab-hd-hgt)', 'scroll-padding-top: var(--ws-tab-hd-hgt)'));
        self::addStyle(WebshipTools::css('.tabbed', [' > ', '.tab'], 'position: absolute',
            'left: 0', 'top: var(--ws-tab-hd-hgt)', 'width: 100%', 'box-sizing: border-box', 'overflow-y: visible',
            'height: 100%', 'padding: 0'));
    }

    /**
     * Creates a new tab.
     * @param string[]|string $headers The header(s).
     * @param string[]|string $classes Some classes.
     * @param string|null $img An optional path to an image.
     * @param string|null $type An optional datatype.
     * @return TabContainerItemHtmlView
     */
    public function tab(array|string $headers = [], array|string $classes = [],
                        string|null $img = null, string|null $type = null) : TabContainerItemHtmlView {
        $view = TabContainerItemHtmlView::create($classes, true, $img, $type);
        $view->ref('tab');
        $view->header($headers);
        $this->add($view);
        return $view;
    }

    /**
     * Create a tab that can be closed.
     * @param string[]|string $headers The header(s).
     * @param string[]|string $classes Some classes.
     * @param string|null $img An optional path to an image.
     * @param string|null $type An optional datatype.
     * @return TabContainerItemHtmlView
     */
    public function closingTab(array|string $headers = [], array|string $classes = [],
                               string|null $img = null, string|null $type = null) : TabContainerItemHtmlView {
        $view = $this->tab($headers, $classes, $img, $type);
        $view->closer()->protocol(WEvent::CLICK)->event(WEvent::CLOSE_TAB, $view);
        return $view;
    }

    /**
     * Return a new tab container html view.
     * @param string[]|string $classes Some classes.
     * @return TabContainerHtmlView
     * @deprecated
     */
    public static function _new(array|string $classes = []) : static {
        return new TabContainerHtmlView($classes);
    }

    /**
     * Return a new tab container html view.
     * @param string[]|string $classes Some classes.
     * @return TabContainerHtmlView
     */
    public static function create(array|string $classes = []) : TabContainerHtmlView {
        return new TabContainerHtmlView($classes);
    }

    /**
     * Sets the left tab opened (i.e., no border).
     * @return $this
     */
    public function leftOpen() : static {
        $this->addClass('lo');
        return $this;
    }
}

?>