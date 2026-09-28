<?php

namespace webship\help;

use Exception;
use webship\interoperability\WEvent;
use webship\core\HtmlView;
use webship\symbols\ImageHtmlView;
use webship\WebshipTools;

/**
 * View HintHtmlView.
 *
 * <p>Displays hints for user guidance in views.</p>
 *
 * <code>
 * $hint = HintHtmlView::create();
 * $pic = new HtmlView('pic');
 * $pic->add(new ImageHtmlView('/webship/pic/light-bulb.svg', 'bulb'));
 * $hint->add($pic);
 * </code>
 * 
 * <img src="http://localhost/coast/webship/modeldoc/hint.png" />
 *
 * @title Hint
 * @category help
 * 
 * @package webship
 * @subpackage php/model/help
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $hint = HintHtmlView::create();
 *     $pic = new HtmlView('pic');
 *     $pic->add(new ImageHtmlView('/webship/pic/light-bulb.svg', 'bulb'));
 *     $hint->add($pic);
 */
class HintHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     * @throws Exception
     */
    private function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['hints'], $classes));

        self::addCSS();

        $this->js('$("' . $this->idc() . '").on("' . WEvent::SHOW_HINT . '", function(event, el, content, hint) {' .
            'let c = $(\'' . HintHtmlView::createHint()->generateSafe() . '\');' .
            '$(c).append(content);' .
            '$(c).attr("hint", hint);' .
            '$(this).append(c);' .
            'event.target = el;' .
            'ms($(this), event);' .
            '});'
        );

        $this->js('$("' . $this->idc() . '").on("' . WEvent::HIDE_HINT . '", function(event, hint) {' .
            '$(this).find("[hint=" + hint + "]").remove();' .
            '});'
        );
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.hints', '',
            'visibility: visible', 'opacity: 1', 'position: absolute',
            'transition: visibility 0s, opacity 0.5s linear', 'z-index: 10000000'));
        self::addStyle(WebshipTools::css('.hints', ':empty', 'width: 0', 'height: 0',
            'visibility: hidden', 'opacity: 0'));

        self::addStyle(WebshipTools::css('.hints', [' > ', '.hint'], 'width: 250px', 'min-height: 40px',
            'background: var(--ws-hint-bg)', 'width: 100%', 'transition: width 0.5s, height 0.5s',
            'padding: 10px', 'font-size: 12px', 'font-style: normal', 'text-transform: none',
            'box-shadow: var(--ws-front-shadow)', 'border-radius: 5px'));
        self::addStyle(WebshipTools::css('.hints', [' > ', '.hint', ' > ', 'ul'], 'list-style-type: none',
            'padding-left: 10px'));

        self::addStyle(WebshipTools::css('.hints', [' > ', '.hint', ' > ', '.pic'], 'width: 25px',
            'height: 25px', 'background: var(--ws-hint-img-bg)', 'border-radius: 12.5px', 'display: flex',
            'align-items: center', 'justify-content: center'));
        self::addStyle(WebshipTools::css('.hints', [' > ', '.hint', ' > ', '.pic', ' > ', '.bulb'],
            'width: 20px'));
    }

    /**
     * Create a new hint.
     * @param string[]|string $classes Some classes.
     * @return HintHtmlView
     * @throws Exception
     */
    public static function create(array|string $classes = []) : HintHtmlView {
        return new HintHtmlView($classes);
    }

    /**
     * Create a new hint internally.
     * @return HtmlView
     */
    private static function createHint() : HtmlView {
        $item = new HtmlView('hint');
        $item->add(new HtmlView('pic'))->
            add(new ImageHtmlView('/webship/pic/light-bulb.svg', 'bulb'));

        return $item;
    }
}
?>