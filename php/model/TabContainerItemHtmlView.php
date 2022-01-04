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
        $this->texts();

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '','z-index: 0'));

        $this->style(Tools::css($coreSelector, array(' > ', '.tab-header'),
            'position: absolute', 'top: -25px', 'height: 25px', 'z-index: 2',
            'background: rgba(255, 255, 255, 1.0)', 'border: solid 1px rgba(0, 0, 0, 1.0)',
            'border-bottom: none', 'display: flex', 'align-items: center', 'padding: 0 5px',
            'box-sizing: border-box', 'cursor: pointer'));

        $this->style(Tools::css($coreSelector, array(' > ', '.tab-header', ' > ', '.closer'),
            'width: 25px', 'height: 25px'));

        $this->style(Tools::css($coreSelector, array(' > ', '.tab-separator'),
            'position: absolute', 'left: 0', 'top: -1px', 'width: 100%', 'box-sizing: border-box',
            'border-bottom: solid 1px rgba(0, 0, 0, 1.0)', 'z-index: 1'));

        $this->style(Tools::css($coreSelector, array(' > ', '.tab-content'),
            'position: absolute', 'width: calc(100% - 5px)', 'top: 0', 'background: rgba(255, 255, 255, 1.0)',
            'height: calc(100% - 30px)', 'overflow-y: auto'));

        $this->style(Tools::css($coreSelector, array(' > ', '.tab-content', ' > ', '.panel'),
            'border: none'));

        if (!$knownParent) $this->js('$("' . $this->idc() . '").trigger("' . Event::NEW_TAB . '");');
        $this->js('$("' . $this->idc() . ' > .tab-header").click(function(event) {' .
            'let max = Math.max.apply(Math, $(this).parent().parent().children(".tab").map((i, c) => parseInt($(c).css("z-index"))));' .
            '$(event.target).parent().css("z-index", max + 1);' .
            'let conOld = $(this).parent().parent().children(".tab").children(".tab-content");' .
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
        if ($this->subviews[0] instanceof TextView) {
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
     * @param integer $language The language.
     * @param string $text The header.
     */
    public function header($language, $text) {
        if ($this->subviews[0] instanceof TextView) {
            $this->subviews[0]->text($language, $text);
        }
        return $this;
    }

    /**
     * Generate a closer view.
     * @param array|string $classes The classes.
     * @return CloserHtmlView
     */
    public function closer($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $closer = new CloserHtmlView($classes);
        $this->add($closer);
        return $closer;
    }
}
?>