<?php

/**
 * Class SwitchHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class SwitchHtmlView extends InputHtmlView {

    private array $localClasses = [];

    /**
     * SwitchHtmlView constructor.
     * @param string $id The id.
     * @param string $name The name.
     * @param array|string $classes The classes.
     */
    public function __construct(string $id, string $name, $classes = array()) {
        parent::__construct($id, $name, 'checkbox');
        $this->localClasses = array_merge(['switch'], is_array($classes) ? $classes : ['classes']);

        $this->style(Tools::css('.switch', '', 'position: relative',
            'display: inline-block', 'width: 60px', 'height: 34px'));
        $this->style(Tools::css('.switch', [' ', 'input'], 'opacity: 0', 'width: 0', 'height: 0'));
        $this->style(Tools::css('.switch', [' ', 'input[type="checkbox"]'], 'height: 0'));
        $this->style(Tools::css('.switch', [' ', '.slider'], 'position: absolute', 'cursor: pointer',
            'top: 0', 'left: 0', 'right: 0', 'bottom: 0', 'background-color: #ccc', '-webkit-transition: .4s',
            'transition: .4s', 'border-radius: 34px'));
        $this->style(Tools::css('.switch', [' ', '.slider', ':before'], 'position: absolute',
            'content: ""', 'height: 26px', 'width: 26px', 'left: 4px', 'bottom: 4px', 'background-color: white',
            '-webkit-transition: .4s', 'transition: .4s', 'border-radius: 50%'));
        $this->style(Tools::css('.switch', [' ', 'input:checked', ' + ', '.slider'],
            'background-color: var(--slider-checked)'));
        $this->style(Tools::css('.switch', [' ', 'input:focus', ' + ', '.slider'],
            'box-shadow: 0 0 1px var(--slider-checked);'));
        $this->style(Tools::css('.switch', [' ', 'input:checked', ' + ', '.slider:before'],
            '-webkit-transform: translateX(26px)', '-ms-transform: translateX(26px)',
            'transform: translateX(26px)'));
        $this->style(Tools::css('.switch', [' ', 'input:disabled', ' + ', '.slider'],
            'background-color: var(--system-background)', 'opacity: 0.5', 'cursor: not-allowed'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() {
        return '<label class="' . implode(' ', $this->localClasses) . '">' . parent::generateOpeningTag();
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() {
        return parent::generateClosingTag() . '<span class="slider"></span></label>';
    }


}
?>