<?php

/**
 * Class ContainerHeaderHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class ContainerHeaderHtmlView extends HtmlView {

    /**
     * ContainerHeaderHtmlView constructor.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('container-header'), $classes));

        $coreClasses = array_diff($this->classes, $classes);
        $coreSelector = '.' . implode('.', $coreClasses);

        $this->style(Tools::css($coreSelector, '', 'font-size: 20px',
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