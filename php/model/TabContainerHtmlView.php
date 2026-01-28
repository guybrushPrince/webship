<?php

/**
 * Class TabContainerHtmlView.
 *
 * @package webship
 * @subpackage php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 */
class TabContainerHtmlView extends ContainerHtmlView {

    public const TAB_HEADER_DISTANCE = 1;

    /**
     * TabContainerHtmlView constructor.
     * @param string[]|string $classes
     */
    public function __construct(array|string $classes = array()) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'tabbed' ], $classes), 'var(--main-menu)');

        $this->style(Tools::css('.tabbed', [], 'position: relative',
            'width: 100%', 'height: 100%', 'overflow-y: clip'/*, 'background: var(--main-menu)'*/));
        $this->style(Tools::css('.tabbed', [' > ', '.tab'], 'position: absolute', 'left: 0', 'top: 27px',
            'width: 100%', 'box-sizing: border-box', 'overflow-y: visible', 'height: 100%', 'padding: 0'));

        $this->js('$("' . $this->idc() . '").on("' . Event::NEW_TAB . '", function(event) {' .
            'if ($(event.target).parent("' . $this->idc() . '").length) {' .
            'let max = Math.max.apply(Math, $(this).children(".tab").map((i, c) => parseInt($(c).css("z-index"))));' .
            'let width = $(this).children(".tab").children(".tab-header").toArray().reduce((acc, c) => acc + parseFloat($(c).css("width")) + ' . self::TAB_HEADER_DISTANCE . ', 0) - ' . self::TAB_HEADER_DISTANCE . ';' .
            '$(event.target).css("z-index", max + 1);' .
            '$(this).children(".tab").removeClass("active");' .
            '$(event.target).addClass("active");' .
            '$(event.target).attr("tabId", "t" + context.tabId++);' .
            'let tabHeader = $(event.target).children(".tab-header");' .
            'let w = (width - parseFloat($(tabHeader).css("width")));' .
            '$(tabHeader).css("left", w === 0 ? -2 : w + /*' . self::TAB_HEADER_DISTANCE . '*/ - 1);' .
            'let conOld = $(this).children(".tab").not(event.target).children(".tab-content");' .
            'conOld.filter(":visible").toArray().forEach((e) => { let p = $(e).parent(); context.tabOffset[p.attr("tabId")] = p.scrollTop(); });' .
            'conOld.hide();' .
            '}' .
            '});');

        $this->js('$("' . $this->idc() . '").on("' . Event::CLOSE_TAB . '", function(event) {' .
            'if ($(event.target).parent("' . $this->idc() . '").length) {' .
            'let missing = parseFloat($(event.target).children(".tab-header").css("width")) + ' . self::TAB_HEADER_DISTANCE . ';' .
            'let next = $(event.target).nextAll();' .
            '$(event.target).trigger("' . Event::DELETE . '").remove();' .
            'next.children(".tab-header").each((i, t) => $(t).css("left", parseFloat($(t).css("left")) - missing));' .
            'let childrenWithZ = $(this).children(".tab").map((i, c) => {return {v: c, z: parseFloat($(c).css("z-index"))};});' .
            'let max = Math.max.apply(Math, childrenWithZ.map((i, c) => c.z));' .
            '$(this).children(".tab").removeClass("active");' .
            'childrenWithZ.each((i, c) => (c.z === max ? (() => { $(c.v).children(".tab-content").show();' .
                '$(c.v).addClass("active");' .
                'let tabId = $(c.v).attr("tabId");' .
                'if (tabId in context.tabOffset) $(c.v).scrollTop(context.tabOffset[tabId]); })()' . ' : ""));' .
            '}' .
            '});');

        $this->js('(() => {' .
            'let that = $("' . $this->idc() . '");' .
            'that.children(".tab").each((i,tab) => {' .
            'let max = Math.max.apply(Math, $(tab).prevAll(".tab").add(tab).map((i, c) => parseInt($(c).css("z-index"))));' .
            'let width = $(tab).prevAll(".tab").add(tab).children(".tab-header").toArray().reduce((acc, c) => acc + parseFloat($(c).css("width")) + ' . self::TAB_HEADER_DISTANCE . ', 0) - ' . self::TAB_HEADER_DISTANCE . ';' .
            '$(tab).css("z-index", max + 1);' .
            '$(tab).attr("tabId", "t" + context.tabId++);' .
            'let tabHeader = $(tab).children(".tab-header");' .
            'let w = (width - parseFloat($(tabHeader).css("width")));' .
            '$(tabHeader).css("left", w === 0 ? -2 : w + /*' . self::TAB_HEADER_DISTANCE . '*/ - 1);' .
            //'$(tabHeader).css("left", (width - parseInt($(tabHeader).css("width"))) + ' . self::TAB_HEADER_DISTANCE . ');' .
            '});' .
            'let f = that.children(".tab").first();' .
            'let max = Math.max.apply(Math, $(that).children(".tab").map((i, c) => parseInt($(c).css("z-index"))));' .
            '$(f).css("z-index", max + 1);' .
            '$(f).addClass("active");' .
            'let conOld = $(that).children(".tab").children(".tab-content");' .
            'conOld.filter(":visible").toArray().forEach((e) => { let p = $(e).parent(); context.tabOffset[p.attr("tabId")] = p.scrollTop(); });' .
            'conOld.hide();' .
            'let conNew = $(f).children(".tab-content");' .
            'conNew.show();' .
            'let p = conNew.parent(); let tabId = p.attr("tabId");' .
            'if (tabId in context.tabOffset) p.scrollTop(context.tabOffset[tabId]);' .
            '})();');
    }

    // Shortcuts

    /**
     * Creates a new tab.
     * @param array $headers The headers.
     * @param string[]|string $classes The classes.
     * @return TabContainerItemHtmlView
     */
    public function tab(array $headers = [], array|string $classes = []) : TabContainerItemHtmlView {
        $view = new TabContainerItemHtmlView($classes, true);
        $view->ref('tab');
        foreach ($headers as $language => $header) {
            $view->header($language, $header);
        }
        $this->add($view);
        return $view;
    }

    /**
     * Create a tab that can be closed.
     * @param array $headers Headers.
     * @param string[]|string $classes Classes.
     * @return TabContainerItemHtmlView
     */
    public function closingTab(array $headers = [], array|string $classes = []) : TabContainerItemHtmlView {
        $view = $this->tab($headers, $classes);
        $view->closer()->
            protocol(Event::CLICK)->
                event(Event::CLOSE_TAB, 'tab');
        return $view;
    }

    /**
     * Return a new tab container html view.
     * @param array|string $classes Classes.
     * @return TabContainerHtmlView
     */
    public static function _new(array|string $classes = []) : static {
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