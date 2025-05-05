<?php

/**
 * Class TabContainerItemHtmlView.
 *
 * @package webship
 * @subpackage php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 */
class TabContainerItemHtmlView extends ContainerItemHtmlView {

    /**
     * TabContainerItemHtmlView constructor.
     * @param string[]|string $classes Some classes.
     * @param bool $knownParent
     */
    public function __construct(string|array $classes = [], bool $knownParent = false) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'tab' ], $classes));

        // Add texts for the header.
        $this->add(new HtmlView())->add(new HtmlView('icon'))->parent()->texts();

        $this->style(Tools::css('.tab', '','z-index: 0'));

        $this->style(Tools::css('.tab', [' > ', '.tab-header'],
            'position: absolute', 'top: -25px', 'height: 25px', 'z-index: 2',
            'background: var(--neutral)', 'border: solid 2px var(--login-border)',
            'border-bottom: none', 'display: flex', 'align-items: center', 'padding: 0 5px',
            'box-sizing: border-box', 'cursor: pointer', 'border-radius: 6px 6px 0 0'));
        $this->style(Tools::css('.tabbed:not(.lo)', [' > ', '.tab:first-child', ' > ', '.tab-header'],
            'border-left: solid 2px var(--login-border)'));
        $this->style(Tools::css('.tab', [' > ', '.tab-header', ' > ', 'div:first-child'],
            'display: flex', 'flex-direction: row', 'flex-wrap: nowrap'));
        $this->style(Tools::css('.tab', [' > ', '.tab-header', ' ', '.icon:empty'],
            'display: none'));
        $this->style(Tools::css('.tab', [' > ', '.tab-header', ' ', '.icon:not(:empty)'],
            'margin-right: 5px', 'width: 15px', 'height: 15px', 'display: flex',
            'align-items: center', 'justify-content: center', 'border-radius: 50%'));
        $this->style(Tools::css('.tab', [' > ', '.tab-header', ' ', '.icon', ' > ', 'img'],
            'height: 11px'));
        $this->style(Tools::css('.tab', [' > ', '.tab-header', ' > ', '.closer'],
            'width: 20px', 'height: 25px', 'padding-right: 0'));
        $this->style(Tools::css('.tab', [':not(.active)', ' > ', '.tab-header'],
            'background: var(--system-background-other)'));
        $this->style(Tools::css('.tab', [':not(.active)', ' > ', '.tab-header:hover'],
            'background: var(--dark-system-background)'));
        $this->style(Tools::css('.tab', ['.active', ' > ', '.tab-header', ' ', '.closer'],
            'text-shadow: none'));
        $this->style(Tools::css('.tab', [':first-child', ' > ', '.tab-header', ' > ', ':first-child'],
            'padding-right: 4px'));
        $this->style(Tools::css('.tab', [':first-child', ' > ', '.tab-header', ' ', '.closer'],
            'display: none'));

        $this->style(Tools::css('.tab', [' > ', '.tab-separator'],
            'position: absolute', 'left: 0', 'top: -2px', 'width: 100%', 'box-sizing: border-box',
            'border-bottom: solid 2px var(--login-border)', 'z-index: 1'));

        $this->style(Tools::css('.tab', [' > ', '.tab-content'], 'box-sizing: border-box',
            'position: absolute', 'width: 100%', 'top: 0', 'background: var(--neutral)',
            'height: calc(100% - 27px)', 'overflow-y: auto'));

        $this->style(Tools::css('.tab', array(' > ', '.tab-content', ' > ', '.panel'),
            'border: none'));

        if (!$knownParent) $this->js('$("' . $this->idc() . '").trigger("' . Event::NEW_TAB . '");');
        $this->js('$("' . $this->idc() . ' > .tab-header").click(function(event) {' .
            '$(this).parent().trigger("' . Event::CHANGE_TAB . '");' .
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
    public function header(int $language, string $text) : static {
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
        $closer = new CloserHtmlView($classes);
        $closer->mini()->plain();
        $closer->notice(Language::ENGLISH, 'Close this tab');
        $closer->notice(Language::GERMAN,  'Tab schließen');
        $this->add($closer);
        return $closer;
    }
}
?>