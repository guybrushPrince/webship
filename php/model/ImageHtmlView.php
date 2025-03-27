<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ImageHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ImageHtmlView extends HtmlView {

    /**
     * The image location
     * @var string
     */
    private string $src;

    /**
     * An alternative text.
     * @var string[]|null
     */
    private ?array $alt;

    /**
     * ImageHtmlView constructor.
     * @param string $src The src.
     * @param string[]|string $classes Some classes
     * @param string[]|null $alt An alternative text.
     */
    public function __construct(string $src, string|array $classes = [], ?array $alt = null) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['image'], $classes));
        $this->src = $src;
        $this->alt = $alt;

        $this->style(Tools::css('.min-img', [], 'width: 20px', 'height: 20px'));
        $this->style(Tools::css('.min-img', [' > ', 'img'], 'width: 100%'));
        $this->style(Tools::css('img.image.cover', [], 'object-fit: cover'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<img src="' . HtmlActionProtocol::$serverURL . $this->src . '" class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->notices ? ' title="' . (array_key_exists(View::getLanguage(), $this->notices) ? $this->notices[View::getLanguage()] : array_pop($this->notices)) . '"' : '') .
            ($this->alt ? ' alt="' . (array_key_exists(View::getLanguage(), $this->alt) ? $this->alt[View::getLanguage()] : array_pop($this->alt)) . '"' : '') .
            '>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return '';
    }

    // Shortcuts

    /**
     * Creates a mini image view.
     * @param string $src The source of the image.
     * @param string|string[] $classes Some classes.
     * @return HtmlView
     */
    public static function mini(string $src, string|array $classes = []) : HtmlView {
        $mini = new HtmlView('min-img');
        $mini->add(new ImageHtmlView($src, $classes));
        return $mini;
    }
}
?>