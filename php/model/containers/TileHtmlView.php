<?php

/**
 * View TileHtmlView.
 *
 * <p>Presents its subviews as <em>tiles</em> being arranged in a horizontal container. Each of the tiles have
 * mouseover effects, are quadratic, and have a font size depending on the size of the tile.</p>
 * <p>Since {@link TileHtmlView} inherits from {@link ContainerHtmlView} all the options of {@link ContainerHtmlView}
 * are available for the view. Furthermore, each tile is a {@link ContainerHtmlView}, thus, it can also use the
 * options of {@link ContainerHtmlView}.</p>
 *
 * <code>
 * $tilesView = TileHtmlView::create();
 * $tilesView->tile()->texts('Tile 1');
 * $tilesView->tile()->texts('Tile 2');
 * $tilesView->tile()->texts('Tile 3');
 * </code>
 * <code>
 * $tilesView = TileHtmlView::create()->padding(10)->centerHorizontal();
 * $tilesView->tile()->container(['hor', 'vcent', 'hcent', 'h100'])->texts('Tile 1');
 * $tilesView->tile()->container(['hor', 'vcent', 'hcent', 'h100'])->texts('Tile 2');
 * $tilesView->tile()->container(['hor', 'vcent', 'hcent', 'h100'])->texts('Tile 3');
 * </code>
 * <img src="http://localhost/coast/webship/modeldoc/tiles.png" />
 *
 * @package webship
 * @subpackge php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @author Mihailo Jovic
 * @version 1.3.0
 *
 * @view
 * @examples
 *     $tilesView = TileHtmlView::create();
 *     $tilesView->tile()->texts('Tile 1');
 *     $tilesView->tile()->texts('Tile 2');
 *     $tilesView->tile()->texts('Tile 3');
 *     $tilesView = TileHtmlView::create()->padding(10)->centerHorizontal();
 *     $tilesView->tile()->container(['hor', 'vcent', 'hcent', 'h100'])->texts('Tile 1');
 *     $tilesView->tile()->container(['hor', 'vcent', 'hcent', 'h100'])->texts('Tile 2');
 *     $tilesView->tile()->container(['hor', 'vcent', 'hcent', 'h100'])->texts('Tile 3');
 */
class TileHtmlView extends ContainerHtmlView {

    /**
     * Constructor.
     * @param int $tileWidth The width (and height) of each tile.
     * @param string[]|string $classes Some additional classes.
     */
    private function __construct(int $tileWidth = 108, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge(['tiles'], $classes));
        $this->gap(10);

        $this->localStyle('--tw: ' . $tileWidth . 'px');
        $this->localStyle('--fs: ' . round($tileWidth / 9, 2) . 'px');

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.tiles', [], 'border: 1px solid var(--light-system-background)'));
        self::addStyle(Tools::css('.tiles', [' > ', '.tile'], 'align-items: center',
            'justify-content: flex-start', 'border: 1px solid var(--system-background-hover)',
            'cursor: pointer', 'transition: background 0.5s',
            'text-align: center', 'background: var(--ultra-light-system-background)',
            'width: var(--tw)', 'max-width: var(--tw)', 'height: var(--tw)', 'font-size: var(--fs)'));
        self::addStyle(Tools::css('.tiles', [' > ', '.tile', ' > ', 'span.small'], 'display: none'));
        self::addStyle(Tools::css('.tiles', [' > ', '.tile', ' > ', 'img'], 'height: 50%',
            'padding: 10px'));
        self::addStyle(Tools::css('.tiles', [' > ', '.tile', ':hover'],
            'background: var(--system-background)'));
        self::addStyle(Tools::css('.tiles', [' > ', '.tile', '.active'],
            'background: var(--system-background-hover)', 'border-color: var(--dark-system-background)'));
    }

    /**
     * Create a tile html view.
     * @param int $tileWidth The width (and height) of each tile.
     * @param string[]|string $classes Some additional classes.
     * @return TileHtmlView
     */
    public static function create(int $tileWidth = 108, array|string $classes = []) : TileHtmlView {
        return new TileHtmlView($tileWidth, $classes);
    }

    /**
     * Create a default type.
     * @param string[]|string $classes Some additional classes.
     * @return ContainerHtmlView
     */
    public function tile(array|string $classes = []) : ContainerHtmlView {
        if (!is_array($classes)) $classes = [$classes];
        $classes = array_merge($classes, ['tile', 'ver', 'p10']);
        $tile = new ContainerHtmlView($classes);
        $this->add($tile);
        return $tile;
    }
}
?>