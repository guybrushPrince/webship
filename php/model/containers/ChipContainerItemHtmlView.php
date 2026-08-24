<?php

namespace webship\containers;

use webship\buttons\CloserHtmlView;
use webship\WebshipTools;

/**
 * View ChipContainerItemHtmlView.
 *
 * <p>An individual chip item displayed within a {@link ChipContainerHtmlView}.</p>
 *
 * <code>
 * $chip = new ChipContainerItemHtmlView();
 * $chip->box()->texts('Chip Content');
 * $chip->closer();
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/chipcontainer.png" />
 * 
 * @title ChipContainerItem
 * @category containers
 * 
 * @package webship
 * @subpackage php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     $chip = new ChipContainerItemHtmlView();
 *     $chip->box()->texts('Chip Content');
 *     $chip->closer();
 */
class ChipContainerItemHtmlView extends ContainerHtmlView {

    /**
     * The content of the chip.
     * @var ContainerHtmlView
     */
    private ContainerHtmlView $box;

    /**
     * ContainerItemHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['chip'], $classes));

        self::addCSS();

        $this->box = ContainerHtmlView::createIt();
        $this->add($this->box);
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.chip', [' > ', 'button.std-button.closer.mini'],
            'min-height: 20px', 'height: 20px', 'padding: 2.5px', 'min-width: 20px', 'width: 20px',
            'background: none'));
        self::addStyle(WebshipTools::css('.chip', [' ', '.container'], 'height: auto', 'padding-top: 0',
            'padding-bottom: 0'));
        self::addStyle(WebshipTools::css('.chip', [' ', '.container-item'], 'height: auto', 'padding-top: 0',
            'padding-bottom: 0'));
        self::addStyle(WebshipTools::css('.chip', [' ', 'select'], 'height: auto'));
    }

    /**
     * Get the box (with the content).
     * @param string[]|string|null $classes Some additional classes (if necessary).
     * @return ContainerHtmlView
     */
    public function box(array|string|null $classes = null) : ContainerHtmlView {
        if (!is_null($classes)) $this->box->addClass($classes);
        return $this->box;
    }

    /**
     * @deprecated
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        if ($this->subviews[0] instanceof CloserHtmlView) {
            $this->subviews[] = array_shift($this->subviews);
        }
        return parent::generateOpeningTag();
    }

    /**
     * Generate a closer view.
     * @param string[]|string $classes Some classes.
     * @return CloserHtmlView
     */
    public function closer(string|array $classes = []) : CloserHtmlView {
        $closer = CloserHtmlView::_new($classes);
        $closer->plain()->mini();
        $this->add($closer);
        return $closer;
    }

}
?>