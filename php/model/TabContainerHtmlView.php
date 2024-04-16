<?php

include_once dirname(__FILE__) . '/ContainerHtmlView.php';

/**
 * Class TabContainerHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TabContainerHtmlView extends ContainerHtmlView {
    /**
     * TabContainerHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('tabbed'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'position: relative',
            'width: 100%', 'height: 100%', 'overflow-y: clip'));
        $this->style(Tools::css($coreSelector, ' > .tab', 'position: absolute', 'left: 0', 'top: 30px',
            'width: 100%', 'box-sizing: border-box', 'overflow-y: visible', 'height: 100%'));

        $this->js('$("' . $this->idc() . '").on("' . Event::NEW_TAB . '", function(event) {' .
            'if ($(event.target).parent("' . $this->idc() . '").length) {' .
            'let max = Math.max.apply(Math, $(this).children(".tab").map((i, c) => parseInt($(c).css("z-index"))));' .
            'let width = $(this).children(".tab").children(".tab-header").toArray().reduce((acc, c) => acc + parseInt($(c).css("width")) + 5, 0) - 5;' .
            '$(event.target).css("z-index", max + 1);' .
            '$(this).children(".tab").removeClass("active");' .
            '$(event.target).addClass("active");' .
            '$(event.target).attr("tabId", "t" + context.tabId++);' .
            'let tabHeader = $(event.target).children(".tab-header");' .
            '$(tabHeader).css("left", (width - parseInt($(tabHeader).css("width"))) + 5);' .
            'let conOld = $(this).children(".tab").not(event.target).children(".tab-content");' .
            'conOld.filter(":visible").toArray().forEach((e) => { let p = $(e).parent(); context.tabOffset[p.attr("tabId")] = p.scrollTop(); });' .
            'conOld.hide();' .
            '}' .
            '});');

        $this->js('$("' . $this->idc() . '").on("' . Event::CLOSE_TAB . '", function(event) {' .
            'if ($(event.target).parent("' . $this->idc() . '").length) {' .
            'let missing = parseInt($(event.target).children(".tab-header").css("width")) + 5;' .
            'let next = $(event.target).nextAll();' .
            '$(event.target).trigger("' . Event::DELETE . '").remove();' .
            'next.children(".tab-header").each((i, t) => $(t).css("left", parseInt($(t).css("left")) - missing));' .
            'let childrenWithZ = $(this).children(".tab").map((i, c) => {return {v: c, z: parseInt($(c).css("z-index"))};});' .
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
            'let width = $(tab).prevAll(".tab").add(tab).children(".tab-header").toArray().reduce((acc, c) => acc + parseInt($(c).css("width")) + 5, 0) - 5;' .
            '$(tab).css("z-index", max + 1);' .
            '$(tab).attr("tabId", "t" + context.tabId++);' .
            'let tabHeader = $(tab).children(".tab-header");' .
            '$(tabHeader).css("left", (width - parseInt($(tabHeader).css("width"))) + 5);' .
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

    // Short cuts

    /**
     * Creates a new tab.
     * @param array $headers The headers.
     * @param string[]|string $classes The classes.
     * @return TabContainerItemHtmlView
     */
    public function tab(array $headers = [], $classes = []) : TabContainerItemHtmlView {
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
    public function closingTab(array $headers = [], $classes = []) : TabContainerItemHtmlView {
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
    public static function _new($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        return new TabContainerHtmlView($classes);
    }
}
?>