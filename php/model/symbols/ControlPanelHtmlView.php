<?php

/**
 * Class ControlPanelHtmlView.
 *
 * @package webship
 * @subpackge php/model/symbols
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class ControlPanelHtmlView extends HtmlView {

    private int $xDim;
    private int $yDim;

    /**
     * Constructor.
     * @param int $xDim The x dimension (in number of cells).
     * @param int $yDim The y dimension(in number of cells).
     * @param bool $border Whether the control panel shall be displayed with or without border.
     * @param string|string[] $classes Some classes.
     */
    private function __construct(int $xDim = 2, int $yDim = 2, bool $border = true, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(['ctr'], $classes));

        if ($border) $this->classes[] = 'wb';

        $this->xDim = $xDim;
        $this->yDim = $yDim;

        $this->style(Tools::css('.ctr', [], 'display: grid',
            '--cell-height: 30px', '--cell-width: 30px',
            'grid-template-columns: repeat(var(--ctr-cols), 1fr)', 'grid-template-rows: repeat(var(--ctr-rows), 1fr)',
            'box-sizing: content-box',
            'min-height: calc(var(--cell-height) * var(--ctr-rows))',
            'min-width: calc(var(--cell-width) * var(--ctr-cols))',
            'height: calc(var(--cell-height) * var(--ctr-rows))',
            'width: calc(var(--cell-width) * var(--ctr-cols))',
            'column-gap: 2px', 'row-gap: 2px', 'padding: 3px',
            'grid-auto-rows: 0', 'overflow: hidden'));
        $this->style(Tools::css('.ctr.wb', [], 'border: 1px solid var(--system-background)',
            'border-radius: 5px'));
        $this->style(Tools::css('.ctr:not(.wb):empty', [], 'display: none'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $tag = substr(parent::generateOpeningTag(), 0, -1);
        $tag .= ' style="--ctr-cols: ' . $this->xDim . '; --ctr-rows: ' . $this->yDim . '">';
        return $tag;
    }

    /**
     * Create a control panel.
     * @param int $xDim The x dimension (in number of cells).
     * @param int $yDim The y dimension(in number of cells).
     * @param bool $border Whether the control panel shall be displayed with or without border.
     * @param string|string[] $classes Some classes.
     * @return ControlPanelHtmlView
     */
    public static function create(int $xDim = 2, int $yDim = 2, bool $border = true,
                                  string|array $classes = []) : ControlPanelHtmlView {
        return new ControlPanelHtmlView($xDim, $yDim, $border, $classes);
    }

    /**
     * Create a button in the control panel.
     * @param string $id The id.
     * @param string $img The path to the image.
     * @return StandardButtonHtmlView
     */
    public function button(string $id, string $img) : StandardButtonHtmlView {
        $but = StandardButtonHtmlView::create($id);
        $this->add($but);
        $but->img($img);
        $but->onlyImg(true);
        return $but;
    }
}
?>