<?php

/**
 * TileHtmlView.
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TileHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param int $tileWidth The width (and height) of each tile.
     * @param string[]|string $classes Some additional classes.
     */
    public function __construct(int $tileWidth = 108, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(['tiles', 'w' . $tileWidth], $classes));

        $this->style(Tools::css('.tiles', [], 'width: 100%', 'display: flex', 'flex-wrap: wrap',
            'flex-direction: row',
            'border: 1px solid rgba(230, 230, 230, 1.0)', 'padding: 10px 10px 0px 10px', 'box-sizing: border-box',
            'justify-content: center'));
        $this->style(Tools::css('.tiles', [' > ', '.tile'], 'display: flex', 'flex-direction: column',
            'align-items: center',
            'justify-content: flex-start', 'margin-right: 10px', 'border: 1px solid rgba(220, 220, 220, 1.0)',
            'padding: 10px', 'box-sizing: border-box', 'cursor: pointer', 'transition: background 0.5s',
            'text-align: center', 'margin-bottom: 10px', 'background: rgba(240, 240, 240, 1.0)'));
        $this->style(Tools::css('.tiles.w' . $tileWidth, [' > ', '.tile'], 'width: ' . $tileWidth . 'px',
            'max-width: ' . $tileWidth . 'px', 'height: ' . $tileWidth . 'px',
            'font-size: ' . round($tileWidth / 9, 2) . 'px'));
        $this->style(Tools::css('.tiles', [' > ', '.tile', ' > ', 'img'], 'height: 50%',
            'padding: 10px'));
        $this->style(Tools::css('.tiles', [' > ', '.tile', ':hover'],
            'background: rgba(200, 200, 200, 1.0)'));
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