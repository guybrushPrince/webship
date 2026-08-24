<?php

namespace webship\buttons;

use webship\WebshipTools;

/**
 * View CloserHtmlView.
 *
 * <p>A <em>closer</em> is just a special button that has a cross as sign.</p>
 *
 * <code>
 * $container = ContainerHtmlView::createIt(['hor', 'gap20', 'vcent']);
 * $container->add(CloserHtmlView::create('hello-world-1'));
 * $container->add(CloserHtmlView::create('hello-world-2'))->mini();
 * $container->add(CloserHtmlView::create('hello-world-3'))->plain();
 * </code>
 * 
 * <img src="http://localhost/coast/webship/modeldoc/closer.png" />
 *
 * @title Closer
 * @category buttons
 * 
 * @package webship
 * @subpackage php/model
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.2.0
 *
 * @view
 * @examples
 *     $container = ContainerHtmlView::createIt(['hor', 'gap20', 'vcent']);
 *     $container->add(CloserHtmlView::create('hello-world-1'));
 *     $container->add(CloserHtmlView::create('hello-world-2'))->mini();
 *     $container->add(CloserHtmlView::create('hello-world-3'))->plain();
 */
class CloserHtmlView extends StandardButtonHtmlView {

    /**
     * CloserHtmlView constructor.
     * @param string[]|string $classes Some classes.
     * @param string|null $img The closer image.
     * @param string|null $id An optional button id.
     */
    private function __construct(string|array $classes = [], string|null $img = '/webship/pic/cancel.svg',
                                ?string $id = null) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(is_null($id) ? 'closer' : $id, array_merge([ 'closer' ], $classes));

        if (is_null($img)) $img = '/webship/pic/cancel.svg';
        $this->img($img);
        $this->onlyImg(true);

        self::addCSS();
    }

    /**
     * Generate the CSS code.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('button.std-button.closer', [], 'box-sizing: border-box'));

        self::addStyle(WebshipTools::css('button.std-button.closer.mini', [], 'min-height: 25px',
            'min-width: 25px', 'height: 25px', 'width: 25px', 'border: none', 'margin: 0', 'background: initial'));

        self::addStyle(WebshipTools::css('button.std-button.closer.plain', [' > ','img'], 'border-radius: 50%',
            'transition: background 0.25s'));
        self::addStyle(WebshipTools::css('button.std-button.closer.plain:hover', [' > ','img'],
            'background: var(--ws-std-bg-dark)'));
    }

    /**
     * Create a closer.
     * @param string|null $id The id (of the button).
     * @param string|string[] $classes Some classes.
     * @param string|null $img A different image if necessary.
     * @return CloserHtmlView
     */
    public static function create(?string $id = null, array|string $classes = [],
                                  string|null $img = '/webship/pic/cancel.svg') : CloserHtmlView {
        return new CloserHtmlView($classes, $img, $id);
    }

    /**
     * Return a new closer html view.
     * @param string[]|string $classes Some classes.
     * @param string|null $img A path to an image.
     * @param string|null $id An optional button id.
     * @return CloserHtmlView
     */
    public static function _new(string|array $classes = [], string|null $img = '/webship/pic/cancel.svg',
                                ?string $id = null) : CloserHtmlView {
        return new CloserHtmlView($classes, $img, $id);
    }

    /**
     * Sets it mini.
     * @return $this
     */
    public function mini() : static {
        $this->addClass('mini');
        return $this;
    }

    /**
     * Sets it plain.
     * @return $this
     */
    public function plain() : static {
        $this->addClass('plain');
        return $this;
    }
}
?>