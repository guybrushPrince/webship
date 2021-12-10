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
            'width: 100%', 'height: 100%', 'overflow-y: hidden'));
        $this->style(Tools::css($coreSelector, ' > .tab', 'position: absolute', 'left: 0', 'top: 30px',
            'width: 100%', 'box-sizing: border-box', 'overflow-y: visible', 'height: 100%'));

        $this->js('$("' . $this->idc() . '").on("' . Event::NEW_TAB . '", function(event) {' .
            'let max = Math.max.apply(Math, $(this).children(".tab").map((i, c) => parseInt($(c).css("z-index"))));' .
            'let width = $(this).children(".tab").find(".tab-header").toArray().reduce((acc, c) => acc + parseInt($(c).css("width")) + 5, 0) - 5;' .
            '$(event.target).css("z-index", max + 1);' .
            '$(event.target).attr("tabId", "t" + context.tabId++);' .
            'let tabHeader = $(event.target).find(".tab-header");' .
            '$(tabHeader).css("left", (width - parseInt($(tabHeader).css("width"))) + 5);' .
            'let conOld = $(this).children(".tab").not(event.target).find(".tab-content");' .
            'conOld.filter(":visible").toArray().forEach((e) => { let p = $(e).parent(); context.tabOffset[p.attr("tabId")] = p.scrollTop(); });' .
            'conOld.hide();' .
            '});');

        $this->js('$("' . $this->idc() . '").on("' . Event::CLOSE_TAB . '", function(event) {' .
            'let missing = parseInt($(event.target).find(".tab-header").css("width")) + 5;' .
            'let next = $(event.target).nextAll();' .
            '$(event.target).remove();' .
            'next.find(".tab-header").each((i, t) => $(t).css("left", parseInt($(t).css("left")) - missing));' .
            'let childrenWithZ = $(this).children(".tab").map((i, c) => {return {v: c, z: parseInt($(c).css("z-index"))};});' .
            'let max = Math.max.apply(Math, childrenWithZ.map((i, c) => c.z));' .
            'childrenWithZ.each((i, c) => (c.z === max ? (() => { $(c.v).find(".tab-content").show();' .
                'let tabId = $(c.v).attr("tabId");' .
                'if (tabId in context.tabOffset) $(c.v).scrollTop(context.tabOffset[tabId]); })()' . ' : ""));' .
            '});');
    }

    // Short cuts

    /**
     * Create a tab that can be closed.
     * @param array $headers Headers.
     * @param array|string $classes Classes.
     * @return TabContainerItemHtmlView
     */
    public function closingTab(array $headers = array(), $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $view = new TabContainerItemHtmlView($classes);
        $view->ref('tab');
        foreach ($headers as $language => $header) {
            $view->header($language, $header);
        }
        $view->closer()->
            protocol(Event::CLICK)->
                event(Event::CLOSE_TAB, 'tab');
        $this->add($view);
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