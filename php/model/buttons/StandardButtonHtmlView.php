<?php

namespace webship\buttons;

use webship\symbols\ImageHtmlView;
use webship\WebshipTools;

/**
 * View StandardButtonHtmlView.
 *
 * <p>A very flexible specialization of a button that can appear differently depending on its configuration.</p>
 *
 * <code>
 * StandardButtonHtmlView::create('example-1')->img('/webship/pic/help.svg')->texts('Press me!');
 * StandardButtonHtmlView::create('example-2')->onlyImg('/webship/pic/help.svg');
 * StandardButtonHtmlView::create('example-3')->plain()->texts('Press me!');
 * StandardButtonHtmlView::create('example-4')->small()->img('/webship/pic/help.svg')->texts('Press me!');
 * StandardButtonHtmlView::create('example-5')->long()->img('/webship/pic/help.svg')->texts('Press me!');
 * StandardButtonHtmlView::create('example-6')->list()->img('/webship/pic/help.svg')->texts('Press me!');
 * </code>
 * <img src="http://localhost/coast/webship/modeldoc/standardbutton.png" />
 *
 * @title StandardButton
 * @category buttons
 * 
 * @package webship
 * @subpackage php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 *
 * @view
 * @examples
 *     StandardButtonHtmlView::create('example-1')->img('/webship/pic/help.svg')->texts('Press me!');
 *     StandardButtonHtmlView::create('example-2')->onlyImg('/webship/pic/help.svg');
 *     StandardButtonHtmlView::create('example-3')->plain()->texts('Press me!');
 *     StandardButtonHtmlView::create('example-4')->small()->img('/webship/pic/help.svg')->texts('Press me!');
 *     StandardButtonHtmlView::create('example-5')->long()->img('/webship/pic/help.svg')->texts('Press me!');
 *     StandardButtonHtmlView::create('example-6')->list()->img('/webship/pic/help.svg')->texts('Press me!');
 */
class StandardButtonHtmlView extends ButtonHtmlView {

    /**
     * Constructor.
     * @param string|null $id The id.
     * @param string[]|string $classes Some classes.
     * @param string|null $type An optional type of the button.
     */
    protected function __construct(?string $id = null, array|string $classes = [], ?string $type = 'button') {
        $classes = is_array($classes) ? $classes : [ $classes ];
        $classes[] = 'std-button';
        if ($type === null) $type = 'button';
        parent::__construct($id, $type, $classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('button.std-button', [], 'display: flex', 'flex-direction: row',
            '--ws-common-width: 175px', '--ws-common-height: 50px', '--ws-bottom-distance: 1rem',
            '--ws-common-margin: .5rem 0 var(--ws-bottom-distance) 0',
            'align-items: center', 'justify-content: center',
            'width: var(--ws-common-width)', 'min-height: var(--ws-common-height)', 'cursor: pointer',
            'border: 1px solid var(--ws-std-bg-dark)', 'background: var(--ws-button-bg)',
            'color: var(--ws-main-font)', 'font-size: 16px', 'text-align: center',
            'font-family: Roboto Condensed, Roboto, sans-serif', 'position: relative',
            'margin: var(--ws-common-margin)',
            '-webkit-transition: -webkit-box-shadow .25s', 'transition: box-shadow .25s',
            '-webkit-box-shadow: var(--ws-haptic-shadow)', 'box-shadow: var(--ws-haptic-shadow)',
            'transition: background 0.5s', 'border-radius: 5px', 'box-sizing: border-box', 'gap: 10px',
            '--ws-hover: var(--ws-std-bg-dark)'));
        self::addStyle(WebshipTools::css('button.std-button', [':hover'],
            'background: var(--ws-hover)'));
        self::addStyle(WebshipTools::css('button.std-button', [' > ', 'img'], 'height: 100%'));

        self::addStyle(WebshipTools::css('button.std-button.plain', [], 'background: none',
            '-webkit-box-shadow: none', 'box-shadow: none'));

        self::addStyle(WebshipTools::css('button.std-button.small', [], 'margin: 0', 'height: 30px',
            'max-height: 30px', 'width: auto', 'min-height: auto'));
        self::addStyle(WebshipTools::css('button.std-button.small', [' > ', 'img'], 'height: 50%'));
        self::addStyle(WebshipTools::css('button.std-button.sm-img', [' > ', 'img'], 'height: 50%',
            'margin: 5px'));

        self::addStyle(WebshipTools::css('button.std-button.w25', [], 'width: 25%'));
        self::addStyle(WebshipTools::css('button.std-button.w50', [], 'width: 50%'));
        self::addStyle(WebshipTools::css('button.std-button.w75', [], 'width: 75%'));
        self::addStyle(WebshipTools::css('button.std-button.w100', [], 'width: 100%'));

        self::addStyle(WebshipTools::css('button.std-button.long', [], 'width: 250px'));
        self::addStyle(WebshipTools::css('button.std-button.sl', [], 'margin: 0'));

        self::addStyle(WebshipTools::css('button.std-button.only-img', [], 'width: 30px', 'height: 30px',
            'padding: 5px', 'margin: 0', 'min-height: 30px', 'max-height: 30px', 'min-width: 30px', 'max-width: 30px'));
        self::addStyle(WebshipTools::css('button.std-button.long.only-img', [], 'width: var(--ws-common-height)',
            'height: var(--ws-common-height)', 'padding: 5px', 'margin: var(--ws-common-margin)',
            'min-height: var(--ws-common-height)', 'max-height: var(--ws-common-height)', 'min-width: var(--ws-common-height)',
            'max-width: var(--ws-common-height)'));
        self::addStyle(WebshipTools::css('button.std-button:disabled', [], 'opacity: 0.25', 'cursor: not-allowed'));

        self::addStyle(WebshipTools::css('button.std-button.list-b', [], 'margin: 0',
            'justify-content: flex-start', 'text-align: left', 'min-height: 40px', 'height: 40px', 'font-size: 14px',
            'border-radius: 0', 'width: 100%'));
        self::addStyle(WebshipTools::css('button.std-button.list-b', [' > ', 'img'], 'height: 50%'));
        self::addStyle(WebshipTools::css('button.std-button.list-b', [' > ', 'img.invisible'],
            'visibility: hidden'));
    }

    /**
     * Creates a new standard button.
     * @param string|null $id The id.
     * @param string|string[] $classes Some classes.
     * @return StandardButtonHtmlView
     */
    public static function create(?string $id = null, string|array $classes = []) : StandardButtonHtmlView {
        return new StandardButtonHtmlView($id, $classes);
    }

    /**
     * Adds an image to the button.
     * @param string $image The button.
     * @param string[]|string $classes Some classes.
     * @return StandardButtonHtmlView $this
     */
    public function img(string $image, bool $small = false, string|array $classes = []) : static {
        $this->add(new ImageHtmlView($image, $classes));
        if (count($this->subviews) > 1) {
            $imgView = array_pop($this->subviews);
            array_unshift($this->subviews, $imgView);
        }
        if ($small) $this->addClass('sm-img');
        return $this;
    }

    /**
     * Set that the button only uses an image.
     * @param bool|string $img Whether it is only an image, or not.
     * @return $this
     */
    public function onlyImg(bool|string $img) : static {
        if (is_string($img)) {
            $this->img($img);
            $img = true;
        }
        if ($img) $this->classes[] = 'only-img';
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

    /**
     * Sets the button small.
     * @return $this
     */
    public function small() : static {
        $this->addClass('small');
        return $this;
    }

    /**
     * Sets the button long.
     * @return $this
     */
    public function long() : static {
        $this->addClass('long');
        return $this;
    }

    /**
     * Sets the button spaceless.
     * @return $this
     */
    public function spaceless() : static {
        $this->addClass('sl');
        return $this;
    }

    /**
     * Sets the width (25%, 50%, 75%, 100%).
     * @param int $width The width.
     * @return $this
     */
    public function width(int $width) : static {
        $this->addClass('w' . $width);
        return $this;
    }

    /**
     * Defines the button to be in a list.
     * @return $this
     */
    public function list() : static {
        $this->addClass('list-b');
        return $this;
    }
}
?>