<?php

/**
 * Class DashboardItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DashboardItemHtmlView extends HtmlView {

    private ?int $colPos;
    private int $colSpan;
    private ?int $rowPos;
    private int $rowSpan;

    /**
     * DashboardItemHtmlView constructor.
     * @param string[]|string $classes
     */
    public function __construct(array|string $classes = [], ?int $colPos = null, int $colSpan = 1, ?int $rowPos = null,
                                int $rowSpan = 1) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['dashboard-item'], $classes));
        $this->colPos = $colPos;
        $this->colSpan = $colSpan;
        $this->rowPos = $rowPos;
        $this->rowSpan = $rowSpan;

        $this->style(Tools::css('.dashboard', [' > ', '.dashboard-item', ' > ', '.box'],
            'overflow-y: auto', 'padding: 5px', 'box-sizing: border-box', 'width: 100%', 'height: 100%'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $id = $this->id();
        return '<div class="' . implode(' ', array_merge($this->classes, array($id))) . '"' .
            ($id ? ' data-id="' . $id . '"' : '') .
            ' style="grid-column: ' .
            ($this->colPos ? $this->colPos . ' / ': '') . 'span ' . $this->colSpan . ';' .
            ' grid-row: ' .
            ($this->rowPos ? $this->rowPos . ' / ': '') . 'span ' . $this->rowSpan . ';' . '"' .
            '>' . (!Tools::isEmpty($this->subviews) ? '<div class="box">' : '');
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return (!Tools::isEmpty($this->subviews) ? '</div>' : '') . parent::generateClosingTag();
    }

    // Shortcuts

    /**
     * @return ?DashboardHtmlView
     */
    public function parent() : ?DashboardHtmlView {
        $p = $this->getParent();
        if ($p instanceof DashboardHtmlView) {
            return $p;
        } else {
            return null;
        }
    }

    /**
     * Generate a closer view.
     * @param string[]|string $classes The classes.
     * @return CloserHtmlView
     */
    public function closer(string|array $classes = []) : CloserHtmlView {
        $closer = new CloserHtmlView($classes);
        $this->add($closer);
        return $closer;
    }

    /**
     * Generate a closer view.
     * @param string[]|string $classes The classes.
     * @return PencilHtmlView
     */
    public function pencil(string|array $classes = []) : PencilHtmlView {
        $pencil = new PencilHtmlView($classes);
        $this->add($pencil);
        return $pencil;
    }

    /**
     * Create a view from the dashboard items.
     * @param DashboardItem $item The dashboard item.
     * @param string[]|string $classes (optional) The classes.
     * @return DashboardItemHtmlView
     */
    public static function _new(DashboardItem $item, array|string $classes = []) : DashboardItemHtmlView {
        return $item->createView($classes);
    }

    /**
     * @return int|null
     */
    public function getColPos(): ?int {
        return $this->colPos;
    }

    /**
     * @return int
     */
    public function getColSpan(): int {
        return $this->colSpan;
    }

    /**
     * @return int|null
     */
    public function getRowPos(): ?int {
        return $this->rowPos;
    }

    /**
     * @return int
     */
    public function getRowSpan(): int {
        return $this->rowSpan;
    }


}
?>