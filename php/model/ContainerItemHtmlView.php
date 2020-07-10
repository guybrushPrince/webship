<?php

include_once dirname(__FILE__) . '/HtmlView.php';

/**
 * Class ContainerItemHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ContainerItemHtmlView extends HtmlView {

    /**
     * ContainerItemHtmlView constructor.
     * @param array $classes
     */
    public function __construct(array $classes = array()) {
        parent::__construct(array_merge(array('container-item'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'display: flex',
            'flex-wrap: wrap', 'padding: 5px', 'box-sizing: border-box'));
        $this->style(Tools::css($coreSelector, '.long', 'flex-wrap: wrap',
            'flex-direction: row'));
        $this->style(Tools::css($coreSelector, '.w100', 'width: 100%'));
        $this->style(Tools::css($coreSelector, '.h-c', 'justify-content: center',
            'align-items: center'));
        $this->style(Tools::css($coreSelector, '.p-0', 'padding: 0'));
    }

    // Short cuts

    /**
     * @return ContainerHtmlView
     */
    public function parent() {
        $p = $this->getParent();
        if ($p instanceof ContainerHtmlView) {
            return $p;
        } else {
            return null;
        }
    }

}
?>