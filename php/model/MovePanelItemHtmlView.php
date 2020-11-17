<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class MovePanelItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class MovePanelItemHtmlView extends PanelItemHtmlView {

    /**
     * Whether it has a delete button or not.
     * @var bool
     */
    private $hasDelete = false;

    /**
     * MovePanelItemHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($hasDelete = false, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('movable'), $classes));
        $this->hasDelete = $hasDelete;

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'position: relative',
            'width: 95%'));
        $this->style(Tools::css($coreSelector, ' > .move.up', 'position: absolute',
            'left: 100%', 'top: 0', 'width: 5%', 'box-sizing: border-box', 'height: 30px'));
        $this->style(Tools::css($coreSelector, array(' > .move.up', ':after'), 'content: "\0025B2"',
            'width: 100%'));
        $this->style(Tools::css($coreSelector, ' > .move.down', 'position: absolute',
            'left: 100%', 'top: calc(100% - 30px)', 'width: 5%', 'box-sizing: border-box', 'height: 30px'));
        $this->style(Tools::css($coreSelector, array(' > .move.down', ':after'), 'content: "\0025BC"',
            'width: 100%'));

        $this->style(Tools::css($coreSelector, ' > .move.delete', 'position: absolute',
            'left: 100%', 'top: calc(50% - 15px)', 'width: 5%', 'box-sizing: border-box', 'height: 30px'));
        $this->style(Tools::css($coreSelector, array(' > .move.delete', ':after'), 'content: "X"',
            'width: 100%'));

        $this->style(Tools::css($coreSelector, array(' > .move'), 'cursor: pointer', 'text-align: center',
            'font-size: 25px', 'color: rgba(0, 0, 0, 0.5)'));
        $this->style(Tools::css($coreSelector, array(' > .move', ':hover'), 'background: rgba(0, 0, 0, 0.25)',
            'color: rgba(0, 0, 0, 1.0)'));




        $this->js('$("' . $this->idc() . '-up' . '.move.up").click(function(event) {' .
            '$(event.target).parent().insertBefore($(event.target).parent().prev());' .
            '$(event.target).trigger("' . Event::MOVEUP . '");' .
            '});');

        $this->js('$("' . $this->idc() . '-down' . '.move.down").click(function(event) {' .
            '$(event.target).parent().insertAfter($(event.target).parent().next());' .
            '$(event.target).trigger("' . Event::MOVEDOWN . '");' .
            '});');

        if ($hasDelete) {
            $this->js('$("' . $this->idc() . '-delete' . '.move.delete").click(function(event) {' .
                '$(event.target).trigger("' . Event::MOVEDELETE . '");' .
                '$(event.target).parent().remove();' .
                '});');
        }
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return parent::generateOpeningTag() .
            '<div class="' . implode(' ', array('move', 'up', $this->id() . '-up')) . '"></div>' .
            ($this->hasDelete ? '<div class="' . implode(' ', array('move', 'delete', $this->id() . '-delete')) . '"></div>' : '');
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '<div class="' . implode(' ', array('move', 'down', $this->id() . '-down')) . '"></div>' .
            parent::generateClosingTag();
    }


}
?>