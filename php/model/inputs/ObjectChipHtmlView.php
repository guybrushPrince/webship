<?php

/**
 * View ObjectChipHtmlView.
 *
 * <p>Represents an object as a "chip" with the type and a name.</p>
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class ObjectChipHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|null $type The data type (as data attribute).
     * @param string|string[] $prefix The prefix.
     * @param string|string[]|HtmlView|null $content The content (possibly empty).
     * @param bool $swap Swap the premier and secondary colors?
     * @param string|null $premierColor The premier color.
     * @param string|null $premierFont The premier font.
     * @param string|null $secondaryColor The secondary color.
     * @param string|null $secondaryFont The secondary font.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(?string $type, string|array $prefix, string|HtmlView|array|null $content,
                                 bool $swap = false, ?string $premierColor = null, ?string $premierFont = null,
                                 ?string $secondaryColor = null, ?string $secondaryFont = null,
                                 array|string $classes = []) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'obj-chip';
        parent::__construct($classes);

        if (!is_null($type)) $this->data($type);

        $prefixView = new HtmlView();
        $this->add($prefixView);
        $prefixView->texts($prefix);

        if (!is_null($content)) {
            $contentView = new HtmlView();
            $this->add($contentView);
            if ($content instanceof HtmlView) {
                $contentView->add($content);
            } else $contentView->texts($content);
        }

        self::addCSS();

        if (!is_null($type)) {
            if (!$swap) {
                $this->localStyle('--premier-color: var(--' . $type . ')');
                $this->localStyle('--secondary-color: var(--' . $type . '-highlight)');
                $this->localStyle('--premier-font: var(--' . $type . '-font)');
                $this->localStyle('--secondary-font: var(--' . $type . '-highlight-font)');
            } else {
                $this->localStyle('--premier-color: var(--' . $type . '-highlight)');
                $this->localStyle('--secondary-color: var(--' . $type . ')');
                $this->localStyle('--premier-font: var(--' . $type . '-highlight-font)');
                $this->localStyle('--secondary-font: var(--' . $type . '-font)');
            }
        } else {
            if ($premierColor) $this->localStyle('--premier-color: ' . $premierColor);
            if ($secondaryColor) $this->localStyle('--secondary-color: ' .$secondaryColor);
            if ($premierFont) $this->localStyle('--premier-font: ' . $premierFont);
            if ($secondaryFont) $this->localStyle('--secondary-font: ' . $secondaryFont);
        }
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.obj-chip', [], 'display: flex', 'flex-direction: row',
            'flex-wrap: nowrap', 'align-items: center', 'border-radius: 5px', 'padding: 0',
            'transition: 0.5s background', 'width: -moz-fit-content', 'width: fit-content',
            'background: var(--premier-color)', 'color: var(--premier-font)', 'gap: 10px'));
        self::addStyle(Tools::css('.obj-chip.clickable', [], 'cursor: pointer',
            'transition: 0.5s opacity'));
        self::addStyle(Tools::css('.obj-chip.clickable:hover', [], 'opacity: 0.8'));

        self::addStyle(Tools::css('.obj-chip', [' > ', 'div:first-child'], 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'align-items: center', 'background: var(--secondary-color)',
            'color: var(--secondary-font)', 'border-right: 2px solid var(--premier-color)', 'font-weight: bold',
            'font-size: 12px', 'padding: 5px 10px', 'box-sizing: border-box', 'height: 100%',
            'border-radius: 5px 0 0 5px', 'white-space: nowrap'));

        self::addStyle(Tools::css('.obj-chip', [' > ', 'div:nth-child(2):not(:first-child):not(.del)'],
            'display: flex', 'flex-direction: row', 'flex-wrap: nowrap', 'align-items: center',
            'padding: 5px 10px 5px 0', 'white-space: nowrap'));

        self::addStyle(Tools::css('.obj-chip', [' ', 'span.acronym'], 'font-weight: bold'));

        self::addStyle(Tools::css('.obj-chip', [' > ', 'div.del'], 'height: 20px'));
    }

    /**
     * Creates a new object chip.
     * @param string|null $type The type of the object (for default color settings).
     * @param string|string[] $prefix The prefix.
     * @param string|string[]|HtmlView|null $content The content (possibly empty).
     * @param string[]|string $classes Some classes.
     * @param bool $swap Swap the premier and secondary colors?
     * @param string|null $premierColor The premier color.
     * @param string|null $premierFont The premier font.
     * @param string|null $secondaryColor The secondary color.
     * @param string|null $secondaryFont The secondary font.
     * @return ObjectChipHtmlView
     */
    public static function create(?string $type, string|array $prefix, string|HtmlView|array|null $content,
                                  array|string $classes = [],
                                  bool $swap = false, ?string $premierColor = null, ?string $premierFont = null,
                                  ?string $secondaryColor = null, ?string $secondaryFont = null) : ObjectChipHtmlView {
        return new ObjectChipHtmlView($type, $prefix, $content, $swap, $premierColor, $premierFont, $secondaryColor,
            $secondaryFont, $classes);
    }

    /**
     * Add a deletion-view to the object chip.
     * @param string|null $id The id of the closer.
     * @param string $img The image.
     * @return HtmlView
     */
    public function del(?string $id = null, string $img = '/tardis/pic/cancel.svg') : HtmlView {
        $delView = new ContainerHtmlView(['del', 'hor', 'vcent', 'hcent']);
        $delView->add(CloserHtmlView::_new([], $img, $id)->plain()->mini());
        $this->add($delView);
        return $delView;
    }

    /**
     * Set the chip clickable.
     * @return $this
     */
    public function clickable() : static {
        $this->addClass('clickable');
        return $this;
    }

}
?>