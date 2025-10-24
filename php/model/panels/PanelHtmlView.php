<?php

/**
 * View PanelHtmlView.
 *
 * <p>A <em>panel</em> is very similar to a {@link ContainerHtmlView} providing its content vertically. Besides that it
 * provides specializations with adding a border, defining a padding and gap between the children views, round its
 * corners, and separating the children views, or coloring them alternating. Subviews should (but must not) be added
 * with {@link PanelHtmlView::item()}.</p>
 *
 * <code>
 * class SpecialView extends PanelHtmlView { ... }
 * PanelHtmlView::createIt();
 * $panel = PanelHtmlView::createIt()->addBorder()->separateChildren()->rounded();
 * $panel->item()->padding(10)->texts("Hello World");
 * $panel->item()->padding(10)->texts("Hello Universe");
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/panel.png" />
 *
 * @package webship
 * @subpackge php/model/panels
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     class SpecialView extends PanelHtmlView { ... }
 *     PanelHtmlView::createIt();
 *     $panel = PanelHtmlView::createIt()->addBorder()->separateChildren()->rounded();
 *     $panel->item()->padding(10)->texts("Hello World");
 *     $panel->item()->padding(10)->texts("Hello Universe");
 */
class PanelHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    protected function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'panel';
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.panel', [], 'display: flex', 'width: 100%',
            'box-sizing: border-box', 'flex-direction: column'));

        self::addStyle(Tools::css('.panel', ['.border'],
            'border: solid 1px var(--system-background)'));
        self::addStyle(Tools::css('.panel', ['.sep-children'],
            'gap: 1px', 'background: var(--system-background)'));
        self::addStyle(Tools::css('.panel', ['.sep-children', ' > ', '.panel-item'],
            'background: var(--neutral)'));

        self::addStyle(Tools::css('.panel', ['.gap'], 'gap: 5px'));
        self::addStyle(Tools::css('.panel', ['.gap5'], 'gap: 5px'));
        self::addStyle(Tools::css('.panel', ['.gap10'], 'gap: 10px'));
        self::addStyle(Tools::css('.panel', ['.gap15'], 'gap: 15px'));
        self::addStyle(Tools::css('.panel', ['.gap20'], 'gap: 20px'));
        self::addStyle(Tools::css('.panel', ['.p'], 'padding: 5px'));
        self::addStyle(Tools::css('.panel', ['.p5'], 'padding: 5px'));
        self::addStyle(Tools::css('.panel', ['.p10'], 'padding: 10px'));
        self::addStyle(Tools::css('.panel', ['.p15'], 'padding: 15px'));
        self::addStyle(Tools::css('.panel', ['.p20'], 'padding: 20px'));

        self::addStyle(Tools::css('.panel', ['.rounded'], 'border-radius: 5px'));
        self::addStyle(Tools::css('.panel', ['.rounded', ' > ', '*:first-child'],
            'border-radius: 5px 5px 0 0'));
        self::addStyle(Tools::css('.panel', ['.rounded', ' > ', '*:last-child'],
            'border-radius: 0 0 5px 5px'));

        self::addStyle(Tools::css('.panel.ac', [' > ', '.panel-item:nth-child(even)'],
            'background: var(--fine-system-background)'));
        self::addStyle(Tools::css('.panel.ac', [' > ', '.panel-item:nth-child(odd)'],
            'background: var(--neutral)'));
    }

    /**
     * Create a panel.
     * @param string[]|string $classes Some classes.
     * @return PanelHtmlView
     */
    public static function createIt(array|string $classes = []) : PanelHtmlView {
        return new PanelHtmlView($classes);
    }

    //
    // Shortcuts
    //

    /**
     * Add a header.
     * @param string[]|string $classes A set of classes.
     * @param string|string[] $title The title (possibly separated by language).
     * @param string|null $dataType The type of the title if desired.
     * @param string|null $img The name of an image.
     * @return PanelHeaderHtmlView
     */
    public function header(array|string $classes = [], array|string|null $title = null, ?string $dataType = null,
                           ?string $img = null) : PanelHeaderHtmlView {
        $headerView = PanelHeaderHtmlView::create($title, $dataType, $img, $classes);
        $this->add($headerView);
        return $headerView;
    }

    /**
     * Create an item.
     * @param string[]|string $classes A set of classes.
     * @return PanelItemHtmlView
     */
    public function item(array|string $classes = []) : PanelItemHtmlView {
        $itemView = new PanelItemHtmlView($classes);
        $this->add($itemView);
        return $itemView;
    }

    /**
     * Set the padding of the panel.
     * @param int $padding The padding.
     * @return $this
     */
    public function padding(int $padding) : static {
        $this->addClass('p' . $padding);
        return $this;
    }

    /**
     * Add a border to the panel.
     * @return $this
     */
    public function addBorder() : static {
        $this->addClass('border');
        return $this;
    }

    /**
     * Add a separation of the children.
     * @return $this
     */
    public function separateChildren() : static {
        $this->addClass('sep-children');
        return $this;
    }

    /**
     * Round the corners.
     * @return $this
     */
    public function rounded() : static {
        $this->addClass('rounded');
        return $this;
    }

    /**
     * Set the children to alternate their colors.
     * @return $this
     */
    public function alternatingColors() : static {
        $this->addClass('ac');
        return $this;
    }
}
?>