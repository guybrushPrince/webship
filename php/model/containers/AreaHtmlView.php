<?php

/**
 * Class AreaHtmlView.
 *
 * @package webship
 * @subpackge php/model/containers
 *
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class AreaHtmlView extends ContainerHtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Some classes.
     */
    public function __construct(string|array $classes = []) {
        parent::__construct($classes);
        $this->classes[] = 'area';

        $this->style(Tools::css('.area', [], 'background: var(--ultra-light-system-background)',
            'border-radius: 10px', 'margin-top: 10px', 'padding: 20px', 'box-sizing: border-box',
            'border: solid 1px var(--system-background)'));
        $this->style(Tools::css('.aggregations', [' > ', '.sub-title'], 'padding: 0',
            'margin-bottom: 10px'));
    }

    /**
     * @inheritDoc
     */
    public function header(array|string $classes = []) : HtmlView|TextView {
        $title = new SubTitleHtmlView();
        $this->add($title);
        return $title->texts();
    }

    /**
     * @inheritDoc
     */
    public function item(array|string $classes = []) : HtmlView {
        $view = new HtmlView();
        $this->add($view);
        return $view;
    }

    /**
     * @inheritDoc
     */
    public static function _new(array|string $classes = []) : static {
        return new AreaHtmlView($classes);
    }


}
?>