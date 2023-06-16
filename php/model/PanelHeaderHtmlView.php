<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class PanelHeaderHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class PanelHeaderHtmlView extends HtmlView {

    /**
     * PanelHeaderHtmlView constructor.
     * @param array|string $classes
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('panel-header'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'font-family: Roboto, sans-serif', 'text-transform: none', 'padding: 0 0 16px 0',
            'font-size: 1.75em', 'font-weight: lighter', 'border-bottom: solid 1px var(--system-background)'));
    }

    // Short cuts

    /**
     * Get the parent.
     * @return ?PanelHtmlView
     */
    public function parent() : ?PanelHtmlView {
        $p = $this->getParent();
        if ($p instanceof PanelHtmlView) {
            return $p;
        } else {
            return null;
        }
    }

}
?>