<?php

/**
 * TileHtmlView.
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TileHtmlView extends ContainerHtmlView {

    /**
     * Constructor.
     * @param int $tileWidth The width (and height) of each tile.
     * @param string[]|string $classes Some additional classes.
     */
    public function __construct(int $tileWidth = 108, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(['tiles'], $classes));

        $this->localStyle('--tw: ' . $tileWidth . 'px');
        $this->localStyle('--fs: ' . round($tileWidth / 9, 2) . 'px');
    }

    /**
     * Create a tile html view.
     * @param int $tileWidth The width (and height) of each tile.
     * @param string|array $classes Some additional classes.
     * @return TileHtmlView
     */
    public static function create(int $tileWidth = 108, string|array $classes = []) : TileHtmlView {
        return new TileHtmlView($tileWidth, $classes);
    }

    /**
     * Create a default type.
     * @param string|array $classes Some additional classes.
     * @return HtmlView
     */
    public function tile(string|array $classes = []) : HtmlView {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'tile';
        $tile = new HtmlView($classes);
        $this->add($tile);
        return $tile;
    }
}
?>