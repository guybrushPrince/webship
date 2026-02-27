<?php

namespace webship\timeline;

use webship\core\HtmlView;
use webship\symbols\ImageHtmlView;
use webship\WebshipTools;

class TimelinePointHtmlView extends HtmlView{

    private HtmlView $left;
    private HtmlView $right;

    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'point';
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.point', [], 'display: flex', 'flex-direction: row',
            'flex-wrap: nowrap', 'width: 100%', 'align-items: center', 'position: relative',
            '--ws-tml-circle-radius: 15px',
            '--ws-tml-info-margin: 5%'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.v-line'], 'width: var(--ws-tml-thickness)',
            'box-sizing: border-box', 'position: absolute', 'border-right: var(--ws-tml-thickness) solid var(--ws-tml-line-color)',
            'height: calc(50% - var(--ws-tml-circle-radius))',
            'left: calc(50% - var(--ws-tml-thickness) / 2)', 'z-index: 0'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.v-line.t'], 'top: 0'));
        self::addStyle(WebshipTools::css('.point:first-child', [' > ', '.v-line.t'], 'display: none'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.v-line.b'], 'bottom: 0'));
        self::addStyle(WebshipTools::css('.point:last-child', [' > ', '.v-line.b'], 'display: none'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.info'], 'width: calc(50% - var(--ws-tml-circle-radius))',
            'box-sizing: border-box'/*, 'position: relative'*/, 'background: var(--ws-tml-info-background)', 'z-index: 1'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.circ'], 'width: calc(2 * var(--ws-tml-circle-radius))',
            'height: calc(2 * var(--ws-tml-circle-radius))',
            'border-radius: var(--ws-tml-circle-radius)', 'border: var(--ws-tml-thickness) solid var(--ws-tml-line-color)',
            'box-sizing: border-box', 'z-index: 1'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.circ', ' > ', 'img'], 'width: 60%',
            'padding: 20%'));
        self::addStyle(WebshipTools::css('.point.next-step', [' > ', '.circ'],
            'border: var(--ws-tml-thickness) dotted var(--ws-tml-line-color)'));
        self::addStyle(WebshipTools::css('.point.next-step', [' > ', '.info:not(:empty)', ' > ', '.box'],
            'border-style: dotted'));
        self::addStyle(WebshipTools::css('.point.problem', [' > ', '.circ'],
            'border: var(--ws-tml-thickness) dotted var(--ws-tml-line-error-color)'));
        self::addStyle(WebshipTools::css('.point.problem', [' > ', '.info:not(:empty)', ' > ', '.h-line'],
            'border-bottom: var(--ws-tml-thickness) dotted var(--ws-tml-line-error-color)'));
        self::addStyle(WebshipTools::css('.point.problem', [' > ', '.info:not(:empty)', ' > ', '.box'],
            'border-color: var(--ws-tml-line-error-color)', 'border-style: dotted'));
        self::addStyle(WebshipTools::css('.point.warning', [' > ', '.circ'],
            'border: var(--ws-tml-thickness) solid var(--ws-tml-line-warning-color)'));
        self::addStyle(WebshipTools::css('.point.warning', [' > ', '.info:not(:empty)', ' > ', '.h-line'],
            'border-bottom: var(--ws-tml-thickness) dotted var(--ws-tml-line-warning-color)'));
        self::addStyle(WebshipTools::css('.point.warning', [' > ', '.info:not(:empty)', ' > ', '.box'],
            'border-color: var(--ws-tml-line-warning-color)', 'border-style: dotted'));

        self::addStyle(WebshipTools::css('.point', [' > ', '.info:empty'], 'visibility: hidden'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.info:not(:empty)'],
            'width: calc(50% - var(--ws-tml-info-margin) - var(--ws-tml-circle-radius))', 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'align-items: center'/*, 'position: relative'*/));
        self::addStyle(WebshipTools::css('.point', [' > ', '.info:not(:empty)', ' > ', '.box'],
            'border-radius: 10px', 'border: var(--ws-tml-thickness) solid var(--ws-tml-line-color)', 'padding: 10px'/*,
            'position: absolute'*/));
        self::addStyle(WebshipTools::css('.point', [' > ', '.info.right:not(:empty)'],
            'justify-content: flex-start', 'margin-right: var(--ws-tml-info-margin)'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.info.right:not(:empty)', ' > ', '.box'],
            'left: var(--ws-tml-h-line-width)'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.info:not(:empty)', ' > ', '.h-line'],
            'height: 0', 'border-bottom: var(--ws-tml-thickness) dotted var(--ws-tml-line-color)',
            'width: var(--ws-tml-h-line-width)'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.info.left:not(:empty)'],
            'justify-content: flex-end', 'margin-left: var(--ws-tml-info-margin)'));
        self::addStyle(WebshipTools::css('.point', [' > ', '.info.left:not(:empty)', ' > ', '.box'],
            'right: var(--ws-tml-h-line-width)'));

        self::addStyle(WebshipTools::css('.point.loading', [], 'background: initial', 'animation: none'));
        self::addStyle(WebshipTools::css('.point.loading', [' > ', '.circ'], 'animation: pulse 2s infinite'));
        self::addStyle(WebshipTools::css('.point.loading', [' > ', '.circ', ' > ', 'img'],
            'animation: rotate 2s infinite'));
        self::addStyle(WebshipTools::css('@-webkit-keyframes rotate', [],
            '0% { transform: rotate(0); } ' .
            '50% { transform: rotate(180deg); } ' .
            '100% { transform: rotate(360deg); }'));
        self::addStyle(WebshipTools::css('@keyframes rotate', [],
            '0% { transform: rotate(0); } ' .
            '50% { transform: rotate(180deg); } ' .
            '100% { transform: rotate(360deg); }'));
    }

    public static function create(?string $img = null, string|array $classes = []) : TimelinePointHtmlView {
        $point = new TimelinePointHtmlView($classes);

        $point->add(new HtmlView(['v-line', 't']));
        $point->left = $point->add(new HtmlView(['info', 'left']));
        $circle = $point->add(new HtmlView('circ'));
        if ($img) $circle->add(new ImageHtmlView($img));
        $point->right = $point->add(new HtmlView(['info', 'right']));
        $point->add(new HtmlView(['v-line', 'b']));
        return $point;
    }

    public static function createLoading(?string $img = null, ?bool $waiting = false) : TimelinePointHtmlView {
        if (!$img) $img = '/webship/pic/loading.svg';
        return self::create($img, !$waiting ? ['loading'] : ['loading', 'waiting']);
    }

    public function left(?HtmlView $view) : HtmlView {
        $box = $this->left->add(new HtmlView('box'));
        $this->left->add(new HtmlView('h-line'));
        if ($view) {
            $box->add($view);
            return $view;
        } else return $box;
    }

    public function right(?HtmlView $view) : HtmlView {
        $this->right->add(new HtmlView('h-line'));
        $box = $this->right->add(new HtmlView('box'));
        if ($view) {
            $box->add($view);
            return $view;
        } else return $box;
    }
}
?>