<?php

/**
 * View ContainerItemHtmlView.
 *
 * <p>A standard subpart within a container that <em>have not</em> to be used but can be used for containers.</p>
 * <p>You should use it from a {@link ContainerHtmlView} via the method [@link ContainerHtmlView::item()}.</p>
 *
 * @package webship
 * @subpackge php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 */
class ContainerItemHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('container-item'), $classes));

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.container', [' > ', '.container-item'], 'display: flex',
            'flex-wrap: wrap', 'padding: 5px', 'box-sizing: border-box'));
        self::addStyle(Tools::css('.container', [' > ', '.container-item', '.long'], 'flex-wrap: wrap',
            'flex-direction: row'));
        self::addStyle(Tools::css('.container', [' > ', '.container-item', '.w100'], 'width: 100%'));
        self::addStyle(Tools::css('.container', [' > ', '.container-item', '.h100'], 'height: 100%'));
        self::addStyle(Tools::css('.container', [' > ', '.container-item', '.scroll'], 'overflow-y: auto'));
        self::addStyle(Tools::css('.container', [' > ', '.container-item', '.p-0'], 'padding: 0'));
        self::addStyle(Tools::css('.container', [' > ', '.container-item', '.h-l'],
            'justify-content: flex-start', 'align-items: center'));
        self::addStyle(Tools::css('.container', [' > ', '.container-item', '.h-c'],
            'justify-content: center', 'align-items: center'));
        self::addStyle(Tools::css('.container', [' > ', '.container-item', '.h-r'],
            'justify-content: flex-end', 'align-items: center'));
    }

    ////////////////////
    // Shortcuts
    ////////////////////

    /**
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