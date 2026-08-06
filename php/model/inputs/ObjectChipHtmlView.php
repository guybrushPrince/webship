<?php

namespace webship\inputs;

use webship\buttons\CloserHtmlView;
use webship\containers\ContainerHtmlView;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View ObjectChipHtmlView.
 *
 * <p>Represents an object as a "chip" with its type and name.</p>
 *
 * <code>
 * ObjectChipHtmlView::create('variable', 'Variable', 'cool_variable')->clickable();
 * $chip = ObjectChipHtmlView::create('survey', 'Survey', 'My survey');
 * $chip->del();
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/objectchip.png" />
 *
 * @name ObjectChipHtmlView
 * @category inputs
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     ObjectChipHtmlView::create('variable', 'Variable', 'cool_variable')->clickable();
 *     $chip = ObjectChipHtmlView::create('survey', 'Survey', 'My survey');
 *     $chip->del();
 */
class ObjectChipHtmlView extends ContainerHtmlView {

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
        $classes = array_merge($classes, ['hor', 'single', 'vcent', 'obj-chip', 'self-fit']);
        parent::__construct($classes);

        if (!is_null($type)) $this->data($type);

        $prefixView = $this->container(['hor', 'single', 'vcent', 'h100', 'fit']);
        $prefixView->texts($prefix);

        if (!is_null($content)) {
            $contentView = $this->container(['hor', 'single', 'vcent', 'h100', 'fit']);
            if ($content instanceof HtmlView) {
                $contentView->add($content);
            } else $contentView->texts($content);
        } else {
            $this->classes[] = 'wc';
        }

        self::addCSS();

        if (!is_null($type)) {
            if (!$swap) {
                $this->localStyle('--ws-premier-color: var(--' . $type . ')');
                $this->localStyle('--ws-secondary-color: var(--' . $type . '-highlight)');
                $this->localStyle('--ws-premier-font: var(--' . $type . '-font)');
                $this->localStyle('--ws-secondary-font: var(--' . $type . '-highlight-font)');
            } else {
                $this->localStyle('--ws-premier-color: var(--' . $type . '-highlight)');
                $this->localStyle('--ws-secondary-color: var(--' . $type . ')');
                $this->localStyle('--ws-premier-font: var(--' . $type . '-highlight-font)');
                $this->localStyle('--ws-secondary-font: var(--' . $type . '-font)');
            }
        } else {
            if ($premierColor) $this->localStyle('--ws-premier-color: ' . $premierColor);
            if ($secondaryColor) $this->localStyle('--ws-secondary-color: ' .$secondaryColor);
            if ($premierFont) $this->localStyle('--ws-premier-font: ' . $premierFont);
            if ($secondaryFont) $this->localStyle('--ws-secondary-font: ' . $secondaryFont);
        }
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.obj-chip', [], 'border-radius: 5px', 'padding: 0',
            'transition: 0.5s background', 'width: -moz-fit-content', 'width: fit-content',
            'background: var(--ws-premier-color)', 'color: var(--ws-premier-font)', 'gap: 10px'));
        self::addStyle(WebshipTools::css('.obj-chip.clickable', [], 'cursor: pointer',
            'transition: 0.5s opacity'));
        self::addStyle(WebshipTools::css('.obj-chip.clickable:hover', [], 'opacity: 0.8'));

        self::addStyle(WebshipTools::css('.obj-chip', [' > ', 'div:first-child'],
            'background: var(--ws-secondary-color)',
            'color: var(--ws-secondary-font)', 'border-right: 2px solid var(--ws-premier-color)', 'font-weight: bold',
            'font-size: 12px', 'padding: 6px 10px', 'border-radius: 5px 0 0 5px', 'white-space: nowrap'));
        self::addStyle(WebshipTools::css('.obj-chip.wc', [' > ', 'div:first-child'],
            'border-right: none', 'border-radius: 5px'));

        self::addStyle(WebshipTools::css('.obj-chip', [' > ', 'div:nth-child(2):not(:first-child):not(.del)'],
            'padding: 5px 10px 5px 0', 'white-space: nowrap'));

        self::addStyle(WebshipTools::css('.obj-chip', [' ', 'span.acronym'], 'font-weight: bold'));

        self::addStyle(WebshipTools::css('.obj-chip', [' > ', 'div.del'], 'height: 20px'));
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
    public static function create(?string $type, string|array $prefix, string|HtmlView|array|null $content = null,
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
    public function del(?string $id = null, string $img = '/webship/pic/cancel.svg') : HtmlView {
        $delView = new ContainerHtmlView(['del', 'hor', 'vcent', 'hcent']);
        $delView->add(CloserHtmlView::create($id, $img)->plain()->mini());
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