<?php

namespace webship\timeline;

use Event;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * Class TimelineHtmlView.
 *
 * @package webship
 * @subpackage php/model
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
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
    public function __construct(string|array $classes = []) {
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
            '$(tl).on("' . Event::NEXT_STEP . '", () => { if (this.next !== null) { ' .
            '$(this.next).removeClass("waiting"); ' .
            '$(this.next).trigger("' . Event::NEXT_STEP . '"); ' .
            'this.getNext(); ' .
            '}});' .
            '$(tl).on("' . Event::NEXT_DEAD . '", () => { if (this.next !== null) { ' .
            '$(this.next).add($(this.next).siblings(".waiting")).trigger("' . Event::NEXT_DEAD . '"); ' .
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
            'flex-direction: column', '--thickness: 3px', '--interval: 30px', '--line-color: var(--survey-trans)',
            '--h-line-width: 5%', '--line-error-color: var(--signal)', '--line-warning-color: var(--similarity-orange)',
            '--line-dead-color: var(--border-6)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.line'],
            'width: calc(50% - var(--thickness) / 2)', 'height: var(--interval)',
            'border-right: var(--thickness) solid var(--line-color)'));

         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.line'],
            'border-right-color: var(--line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.point', ' ', '.circ'],
            'border-color: var(--line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' > ', '.circ'],
            'border-color: var(--line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' > ', '.circ', ' ~ ', '.v-line'],
            'border-right-color: var(--line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.point', ' ', '.v-line'],
            'border-right-color: var(--line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.timeline-branch', ' ', '.line'],
            'border-right-color: var(--line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.timeline-branch', ' ', '.circ'],
            'border-color: var(--line-dead-color)'));
         self::addStyle(WebshipTools::css('.timeline', [' > ', '.point.dead', ' ~ ', '.timeline-branch', ' ', '.v-line'],
            'border-right-color: var(--line-dead-color)'));
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