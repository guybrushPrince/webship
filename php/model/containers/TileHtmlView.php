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

        parent::addCSS();
        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.tiles', [], 'border: 1px solid var(--light-system-background)',
            'padding: 10px 10px 0px 10px'));
        self::addStyle(Tools::css('.tiles', [' > ', '.tile'], 'display: flex', 'flex-direction: column',
            'align-items: center',
            'justify-content: flex-start', 'margin-right: 10px', 'border: 1px solid var(--system-background-hover)',
            'padding: 10px', 'box-sizing: border-box', 'cursor: pointer', 'transition: background 0.5s',
            'text-align: center', 'margin-bottom: 10px', 'background: var(--ultra-light-system-background)',
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
    public function tile(string|array $classes = []) : ContainerHtmlView {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'tile';
        $tile = new ContainerHtmlView($classes);
        $this->add($tile);
        return $tile;
    }
}
?>