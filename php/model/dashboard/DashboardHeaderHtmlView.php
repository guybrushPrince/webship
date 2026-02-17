<?php

namespace webship\dashboards;

use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * Class DashboardHeaderHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class DashboardHeaderHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(array_merge(['dashboard-header'], $classes));

        $this->style(WebshipTools::css('.dashboard-header', [], 'font-size: 20px',
            'text-transform: uppercase', 'font-weight: lighter', 'width: 100%'));
    }

    // Short cuts

    /**
     * Get the parent.
     * @return ?DashboardHtmlView
     */
    public function parent() : ?DashboardHtmlView {
        $p = $this->getParent();
        if ($p instanceof DashboardHtmlView) {
            return $p;
        } else {
            return null;
        }
    }

}
?>