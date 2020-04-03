<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class MovePanelItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class MovePanelItemHtmlView extends PanelItemHtmlView {

    /**
     * MovePanelItemHtmlView constructor.
     * @param array $classes
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('movable'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'position: relative',
            'width: 95%'));
        $this->style(Tools::css($coreSelector, ' > .move.up', 'position: absolute',
            'left: 100%', 'top: 0', 'width: 5%', 'box-sizing: border-box', 'height: 30px'));
        $this->style(Tools::css($coreSelector, array(' > .move.up', ':after'), 'content: "\0025B2"', // &#x25B2;
            'width: 100%'));
        $this->style(Tools::css($coreSelector, ' > .move.down', 'position: absolute',
            'left: 100%', 'top: calc(100% - 30px)', 'width: 5%', 'box-sizing: border-box', 'height: 30px'));
        $this->style(Tools::css($coreSelector, array(' > .move.down', ':after'), 'content: "\0025BC"', // &#x25bc;
            'width: 100%'));

        $this->style(Tools::css($coreSelector, array(' > .move'), 'cursor: pointer', 'text-align: center',
            'font-size: 25px', 'color: rgba(0, 0, 0, 0.5)'));
        $this->style(Tools::css($coreSelector, array(' > .move', ':hover'), 'background: rgba(0, 0, 0, 0.25)',
            'color: rgba(0, 0, 0, 1.0)'));



        $this->js('$("' . $this->idc() . '.move.up").click(function(event) {' .
            '$(event.target).parent().insertBefore($(event.target).parent().prev());' .
            '$(event.target).trigger("' . Event::MOVEUP . '");' .
            '});');

        $this->js('$("' . $this->idc() . '.move.down").click(function(event) {' .
            '$(event.target).parent().insertAfter($(event.target).parent().next());' .
            '$(event.target).trigger("' . Event::MOVEDOWN . '");' .
            '});');
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return parent::generateOpeningTag() .
            '<div class="' . implode(' ', array('move', 'up', $this->id())) . '"></div>' .
            '<div class="' . implode(' ', array('move', 'down', $this->id())) . '"></div>';
    }

}
?>