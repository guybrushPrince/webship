<?php

namespace webship\symbols;

use ship\views\View;
use webship\containers\ContainerHtmlView;
use webship\core\HtmlView;
use webship\protocols\HtmlActionProtocol;
use webship\WebshipTools;

/**
 * View ImageHtmlView.
 *
 * <p>Presents an image.</p>
 * <p>The path to the images is relative to the basic path of the application.</p>
 * <p>Although the instantiation over the constructor is possible, it is recommended to use
 * {@link ImageHtmlView::create()}.</p>
 *
 * <code>
 * ImageHtmlView::create('/webship/pic/wand.svg', 'A wand')->localStyle('width: 50px');
 * ImageHtmlView::mini('/webship/pic/wand.svg', 'A wand');
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/image.png" />
 *
 * @title Image
 * @category symbols
 * 
 * @package webship
 * @subpackage php/model/symbols
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.5.0
 *
 * @view
 * @examples
 *     ImageHtmlView::create('/webship/pic/wand.svg', 'A wand')->localStyle('width: 50px');
 *     ImageHtmlView::mini('/webship/pic/wand.svg', 'A wand');
 */
class ImageHtmlView extends HtmlView {

    /**
     * The image location
     * @var string
     */
    private string $src;

    /**
     * An alternative text.
     * @var string[]|string|null
     */
    private array|string|null $alt;

    /**
     * Constructor.
     * @param string $src The src.
     * @param string[]|string $classes Some classes
     * @param string[]|string|null $alt An alternative text.
     */
    public function __construct(string $src, array|string $classes = [], array|string|null $alt = null) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'image' ], $classes));
        $this->src = $src;
        $this->alt = $alt;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.container.min-img', [], 'width: 20px', 'height: 20px'));
        self::addStyle(WebshipTools::css('.min-img', [' > ', 'img'], 'width: 100%'));
        self::addStyle(WebshipTools::css('img.image.cover', [], 'object-fit: cover'));
        self::addStyle(WebshipTools::css('img.image.circle', [], 'border-radius: 50%'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        if (is_array($this->alt)) {
            if (array_key_exists(View::getLanguage(), $this->alt)) $alt = $this->alt[View::getLanguage()];
            else $alt = array_pop($this->alt);
        } else $alt = $this->alt;

        $tag = parent::generateOpeningTag();
        $tag = substr($tag, 4);

        return '<img src="' . HtmlActionProtocol::getServerURL() . $this->src . '" ' .
            ($alt ? ' alt="' . $alt . '"' : '') .
            $tag;
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '';
    }

    /**
     * Create an image.
     * @param string $src The src.
     * @param string[]|string|null $alt An alternative text.
     * @param string[]|string $classes Some classes
     * @return ImageHtmlView
     */
    public static function create(string $src, array|string|null $alt = null,
                                  array|string $classes = []) : ImageHtmlView {
        return new ImageHtmlView($src, $classes, $alt);
    }

    /**
     * Creates a mini image view.
     * @param string $src The source of the image.
     * @param string[]|string|null $alt An alternative text.
     * @param string|string[] $classes Some classes.
     * @param bool $asCircle Whether the image shall be represented as circle, or not.
     * @return HtmlView
     */
    public static function mini(string $src, array|string|null $alt = null, string|array $classes = [],
                                bool $asCircle = false) : HtmlView {
        $mini = new ContainerHtmlView(['hor', 'vcent', 'min-img']);
        $img = ImageHtmlView::create($src, $alt, $classes);
        if ($asCircle) $img->circle();
        $mini->add($img);
        return $mini;
    }

    /**
     * Makes a circle out of the image.
     * @return $this
     */
    public function circle() : static {
        $this->addClass('circle');
        return $this;
    }
}
?>