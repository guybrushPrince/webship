<?php

namespace webship\timeline;

use webship\interoperability\WEvent;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View TimelineHtmlView.
 *
 * <p>Presents a timeline view which can display events chronologically with points and branches.</p>
 *
 * <code>
 * $timeline = TimelineHtmlView::create(true);
 * $point = $timeline->point();
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/timeline.png" />
 * 
 * @title Timeline
 * @category timeline
 * 
 * @package webship
 * @subpackage php/model/timeline
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $timeline = TimelineHtmlView::create(true);
 *     $point = $timeline->point();
 */
class TimelineHtmlView extends HtmlView {

    /**
     * Whether the timeline shall start with a line, or not.
     * @var bool
     */
    private bool $startWithLine = false;

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(string|array $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'timeline';
        parent::__construct($classes);

        $this->js('(function(tl) {' .
            'this.getNext = function() {' .
            'let childs = $(tl).children(".waiting");' .
            'this.next = (childs.length > 0 ? childs[0] : null);' .
            'console.log(this.next);' .
            '};' .
            'this.getNext();' .
            '$(tl).on("' . WEvent::NEXT_STEP . '", () => { if (this.next !== null) { ' .
            '$(this.next).removeClass("waiting"); ' .
            '$(this.next).trigger("' . WEvent::NEXT_STEP . '"); ' .
            'this.getNext(); ' .
            '}});' .
            '$(tl).on("' . WEvent::NEXT_DEAD . '", () => { if (this.next !== null) { ' .
            '$(this.next).add($(this.next).siblings(".waiting")).trigger("' . WEvent::NEXT_DEAD . '"); ' .
            'this.next = null; ' .
            '} });' .
            'console.log(tl);' .
            '})($("' . $this->idc() . '"));');

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
         self::addStyle(WebshipTools::css('.timeline', [], 'width: 100%', 'height: 100%', 'display: flex',
            'flex-direction: column', '--ws-tml-thickness: 3px', '--ws-tml-interval: 30px', '--ws-tml-h-line-width: 5%'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.line'],
            'width: calc(50% - var(--ws-tml-thickness) / 2)', 'height: var(--ws-tml-interval)',
            'border-right: var(--ws-tml-thickness) solid var(--ws-tml-line-color)'));

         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.line'],
            'border-right-color: var(--ws-tml-line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.point', ' ', '.circ'],
            'border-color: var(--ws-tml-line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' > ', '.circ'],
            'border-color: var(--ws-tml-line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' > ', '.circ', ' ~ ', '.v-line'],
            'border-right-color: var(--ws-tml-line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.point', ' ', '.v-line'],
            'border-right-color: var(--ws-tml-line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.timeline-branch', ' ', '.line'],
            'border-right-color: var(--ws-tml-line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.timeline-branch', ' ', '.circ'],
            'border-color: var(--ws-tml-line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.timeline-branch', ' ', '.v-line'],
            'border-right-color: var(--ws-tml-line-dead-color)'));
    }

    /**
     * Create a timeline.
     * @param bool $startWithLine Whether the timeline shall start with a line, or not.
     * @param string|string[] $classes Some classes.
     * @return TimelineHtmlView
     */
    public static function create(bool $startWithLine = false, string|array $classes = []) : TimelineHtmlView {
        $timeline = new TimelineHtmlView($classes);
        $timeline->startWithLine = $startWithLine;
        return $timeline;
    }

    /**
     * Creates a point in the timeline.
     * @param string|null $img An optional image.
     * @param string|array $classes Optional classes.
     * @return TimelinePointHtmlView
     */
    public function point(?string $img = null, string|array $classes = []) : TimelinePointHtmlView {
        $this->addClosingBranch();
        if (!WebshipTools::isEmpty($this->subviews) || $this->startWithLine) $this->add(new HtmlView('line'));
        $point = TimelinePointHtmlView::create($img, $classes);
        $this->add($point);
        return $point;
    }

    /**
     * Adds a point to the timeline.
     * @param TimelinePointHtmlView $point The point to add.
     * @return TimelinePointHtmlView
     */
    public function _point(TimelinePointHtmlView $point) : TimelinePointHtmlView {
        $this->addClosingBranch();
        if (!WebshipTools::isEmpty($this->subviews) || $this->startWithLine) $this->add(new HtmlView('line'));
        $this->add($point);
        return $point;
    }

    /**
     * Adds a closing branch (if necessary).
     * @return void
     */
    private function addClosingBranch() : void {
        if (WebshipTools::isEmpty($this->subviews)) $branching = null;
        else $branching = $this->subviews[array_key_last($this->subviews)];
        if ($branching instanceof TimelineBranchHtmlView) {
            $branching->close();
        }
    }

    /**
     * Creates a new branch.
     * @param string|string[] $classes Some classes.
     * @return TimelineHtmlView
     */
    public function branch(string|array $classes = []) : TimelineHtmlView {
        if (WebshipTools::isEmpty($this->subviews)) $branching = null;
        else $branching = $this->subviews[array_key_last($this->subviews)];
        if (!($branching instanceof TimelineBranchHtmlView)) {
            if (!WebshipTools::isEmpty($this->subviews) || $this->startWithLine) $this->add(new HtmlView('line'));
            $branching = $this->add(TimelineBranchHtmlView::create($classes));
        }
        return $branching->branch();
    }

}

?>