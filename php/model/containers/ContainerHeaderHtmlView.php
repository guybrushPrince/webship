<?php

/**
 * View ContainerHeaderHtmlView.
 *
 *  <p>A standard header within a container that <em>have not</em> to be used but can be used.</p>
 *  <p>You should use it from a {@link ContainerHtmlView} via the method [@link ContainerHtmlView::header()}.</p>
 *
 * @package webship
 * @subpackge php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 */
class ContainerHeaderHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'container-header' ], $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.container-header', [], 'font-size: 20px',
            'text-transform: none', 'font-weight: lighter', 'font-family: Roboto, sans-serif', 'width: 100%',
            'box-sizing: border-box'));
    }

    /**
     * Get the parent.
     * @return ?ContainerHtmlView
     */
    public function parent() : ?ContainerHtmlView {
        $p = $this->getParent();
        if ($p instanceof ContainerHtmlView) {
            return $p;
        } else {
            return null;
        }
    }
}
?>