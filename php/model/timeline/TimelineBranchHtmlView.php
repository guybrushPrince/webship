<?php

namespace webship\timeline;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * Class TimelineBranchHtmlView.
 *
 * @package webship
 * @subpackage php/model
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class TimelineBranchHtmlView extends HtmlView {

    /**
     * A box for the branches.
     * @var HtmlView
     */
    private HtmlView $box;

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'timeline-branch';
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.timeline-branch', [], 'width: 100%', 'display: flex',
            'flex-direction: column'));
        self::addStyle(WebshipTools::css('.timeline-branch', [' > ', '.line'], 'box-sizing: border-box',
            'height: var(--ws-tml-thickness)', 'width: calc((100% / var(--ws-tml-branches)) + var(--ws-tml-thickness))',
            'margin: 0 calc((100% / (2 * var(--ws-tml-branches))) - var(--ws-tml-thickness) / 2)'));
        self::addStyle(WebshipTools::css('.timeline-branch', [' > ', '.line.opening'],
            'border-top: var(--ws-tml-thickness) solid var(--ws-tml-line-color)',
            'border-radius: var(--ws-tml-thickness) var(--ws-tml-thickness) 0 0'));
        self::addStyle(WebshipTools::css('.timeline-branch', [' > ', '.line.closing'],
            'border-bottom: var(--ws-tml-thickness) solid var(--ws-tml-line-color)',
            'border-radius: 0 0 var(--ws-tml-thickness) var(--ws-tml-thickness)'));
        self::addStyle(WebshipTools::css('.timeline-branch', [' > ', '.box'], 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap'));
        self::addStyle(WebshipTools::css('.timeline-branch', [' > ', '.box', ' > ', '.timeline'],
            'flex: 1'));
    }

    /**
     * Create a new timeline branch.
     * @param string|string[] $classes Some classes.
     * @return TimelineBranchHtmlView
     */
    public static function create(string|array $classes = []) : TimelineBranchHtmlView {
        $branching = new TimelineBranchHtmlView($classes);
        $branching->add(new HtmlView(['line', 'opening']));
        $branching->box = new HtmlView('box');
        $branching->add($branching->box);
        return $branching;
    }

    /**
     * Create a new branch (which is a new timeline).
     * @return TimelineHtmlView
     */
    public function branch() : TimelineHtmlView {
        $branch = TimelineHtmlView::create(true);
        $this->box->add($branch);
        return $branch;
    }

    /**
     * Adds a new branch.
     * @param TimelineHtmlView $branch The branch to add.
     * @return TimelineHtmlView
     */
    public function _branch(TimelineHtmlView $branch) : TimelineHtmlView {
        $this->box->add($branch);
        return $branch;
    }

    /**
     * Adds a converging line.
     * @return void
     */
    public function close() : void {
        foreach ($this->box->subviews as $branch) $branch->add(new HtmlView('line'));
        $this->add(new HtmlView(['line', 'closing']));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $open = parent::generateOpeningTag();
        return substr($open, 0, -1) . ' style="--ws-tml-branches: ' . (count($this->box->subviews)) . '">';
    }
}
?>