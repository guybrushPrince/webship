<?php

include_once dirname(__FILE__) . '/ContainerItemHtmlView.php';

/**
 * Class TabContainerItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TabContainerItemHtmlView extends ContainerItemHtmlView {

    /**
     * TabContainerItemHtmlView constructor.
     * @param string[]|string $classes Some classes.
     * @param bool $knownParent
     */
    public function __construct($classes = array(), bool $knownParent = false) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('tab'), $classes));

        // Add texts for the header.
        $this->add(new HtmlView())->add(new HtmlView('icon'))->parent()->texts();

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '','z-index: 0'));

        $this->style(Tools::css($coreSelector, [' > ', '.tab-header'],
            'position: absolute', 'top: -25px', 'height: 25px', 'z-index: 2',
            'background: var(--neutral)', 'border: solid 1px var(--border-1)',
            'border-bottom: none', 'display: flex', 'align-items: center', 'padding: 0 5px',
            'box-sizing: border-box', 'cursor: pointer', 'border-radius: 6px 6px 0 0'));
        $this->style(Tools::css($coreSelector, [' > ', '.tab-header', ' > ', 'div:first-child'],
            'display: flex', 'flex-direction: row', 'flex-wrap: nowrap'));
        $this->style(Tools::css($coreSelector, [' > ', '.tab-header', ' ', '.icon:empty'],
            'display: none'));
        $this->style(Tools::css($coreSelector, [' > ', '.tab-header', ' ', '.icon:not(:empty)'],
            'margin-right: 5px', 'width: 15px', 'height: 15px', 'display: flex',
            'align-items: center', 'justify-content: center', 'border-radius: 50%'));
        $this->style(Tools::css($coreSelector, [' > ', '.tab-header', ' ', '.icon', ' > ', 'img'],
            'height: 11px'));
        $this->style(Tools::css($coreSelector, [' > ', '.tab-header', ' > ', '.closer'],
            'width: 25px', 'height: 25px'));
        $this->style(Tools::css($coreSelector, [':not(.active)', ' > ', '.tab-header'],
            'background: var(--system-background-other)'));
        /*$this->style(Tools::css($coreSelector, ['.active', ' > ', '.tab-header'],
            'text-shadow: 0px 0px 1px black'));*/
        $this->style(Tools::css($coreSelector, ['.active', ' > ', '.tab-header', ' ', '.closer'],
            'text-shadow: none'));
        $this->style(Tools::css($coreSelector, [':first-child', ' > ', '.tab-header', ' > ', ':first-child'],
            'padding-right: 4px'));
        $this->style(Tools::css($coreSelector, [':first-child', ' > ', '.tab-header', ' ', '.closer'],
            'display: none'));

        $this->style(Tools::css($coreSelector, array(' > ', '.tab-separator'),
            'position: absolute', 'left: 0', 'top: -1px', 'width: 100%', 'box-sizing: border-box',
            'border-bottom: solid 1px var(--border-1)', 'z-index: 1'));

        $this->style(Tools::css($coreSelector, array(' > ', '.tab-content'), 'box-sizing: border-box',
            'position: absolute', 'width: calc(100% - 5px)', 'top: 0', 'background: var(--neutral)',
            'height: calc(100% - 30px)', 'overflow-y: auto'));

        $this->style(Tools::css($coreSelector, array(' > ', '.tab-content', ' > ', '.panel'),
            'border: none'));

        if (!$knownParent) $this->js('$("' . $this->idc() . '").trigger("' . Event::NEW_TAB . '");');
        $this->js('$("' . $this->idc() . ' > .tab-header").click(function(event) {' .
            'let tabs = $(this).parent().parent().children(".tab"); ' .
            'tabs.removeClass("active");' .
            'let max = Math.max.apply(Math, tabs.map((i, c) => parseInt($(c).css("z-index"))));' .
            '$(this).parent().css("z-index", max + 1);' .
            '$(this).parent().addClass("active");' .
            'let conOld = tabs.children(".tab-content");' .
            'conOld.filter(":visible").toArray().forEach((e) => { let p = $(e).parent(); context.tabOffset[p.attr("tabId")] = p.scrollTop(); });' .
            'conOld.hide();' .
            'let conNew = $(this).parent().children(".tab-content");' .
            'conNew.show();' .
            'let p = conNew.parent(); let tabId = p.attr("tabId");' .
            'if (tabId in context.tabOffset) p.scrollTop(context.tabOffset[tabId]);' .
            '});');


    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
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
    public function generateClosingTag() {
        return '</div>' . parent::generateClosingTag();
    }



    // Short cuts

    /**
     * Set the header of the tab.
     * @param int $language The language.
     * @param string $text The header.
     * @return $this
     */
    public function header(int $language, string $text) : TabContainerItemHtmlView {
        if ($this->subviews[0]->subviews[1] instanceof TextView) {
            $this->subviews[0]->subviews[1]->text($language, $text);
        }
        return $this;
    }

    /**
     * Enables to add an image to the tab.
     * @param string $path The path to the image.
     * @return $this
     */
    public function img(string $path, ?string $type = null) : TabContainerItemHtmlView {
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
        $closer = new CloserHtmlView($classes);
        $closer->mini()->plain();
        $closer->notice(Language::ENGLISH, 'Close this tab');
        $closer->notice(Language::GERMAN,  'Tab schließen');
        $this->add($closer);
        return $closer;
    }
}
?>