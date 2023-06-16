<?php

/**
 * Class WizardHtmlView.
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
abstract class WizardHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Additional classes.
     */
    public function __construct(array|string $classes = []) {
        parent::__construct(array_merge(['wizard'], is_array($classes) ? $classes : [ $classes ]));

        $this->style(Tools::css('.wizard', [], 'width: 100%', 'display: flex',
            'flex-direction: column', 'flex-wrap: wrap', 'position: relative',
            '-webkit-transition: -webkit-box-shadow .25s',
            'transition: box-shadow .25s', 'border-radius: 2px',
            '-webkit-box-shadow: var(--shadow-flow-1)',
            'box-shadow: var(--shadow-flow-1)'));
        $this->style(Tools::css('.wizard', [':hover'],
            '-webkit-box-shadow: var(--shadow-flow-1-hover)',
            'box-shadow: var(--shadow-flow-1-hover)'));
        $this->style(Tools::css('.wizard', [' > ', '.title'], 'color: var(--main-font)',
            'width: 100%', 'max-width: 100%', 'padding: 24px', 'font-size: 24px',
            'font-weight: 300', 'box-sizing: border-box', 'background: var(--card-title-background)',
            'border-bottom: solid 1px var(--wizard-title-border)'));
        $this->style(Tools::css('.wizard', [' > ', '.explanation'], 'padding: 20px'));
        $this->style(Tools::css('.wizard', [' > ', '.active', ' ', '.tiles'], 'border: 0'));
        $this->style(Tools::css('.wizard', [' > ', '.controller'], 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'flex: 1 0 0'));
        $this->style(Tools::css('.wizard', [' > ', '.controller', ' ', '.button'], 'display: flex',
            'flex-direction: row', 'align-items: center', 'justify-content: center', 'cursor: pointer', 'border: 0',
            'transition: 0.5s background'));
        $this->style(Tools::css('.wizard', [' > ', '.controller', ' ', '.button.abort'],
            'width: 50px', 'background: var(--wizard-abort)'));
        $this->style(Tools::css('.wizard', [' > ', '.controller', ' ', '.button.abort', ':hover'],
            'background: var(--wizard-abort-hover) !important'));
        $this->style(Tools::css('.wizard', [' > ', '.controller', ' ', '.button', ' > ', 'img'],
                'height: 20px', 'margin-left: 10px'));
        $this->style(Tools::css('.wizard', [' > ', '.controller', ' ', '.button.abort', ' > ', 'img'],
            'height: 20px', 'margin: 15px'));
    }

    /**
     * Create a title.
     * @return HtmlView
     */
    public function title() : HtmlView {
        return $this->add(new HtmlView('title'));
    }

    /**
     * Create an explanation.
     * @return HtmlView
     */
    public function explanation() : HtmlView {
        return $this->add(new HtmlView('explanation'));
    }

    /**
     * Create the area for active content (where the user must do something).
     * @return HtmlView
     */
    public function active() : HtmlView {
        return $this->add(new HtmlView('active'));
    }

    /**
     * Create the area for controlling the wizard (i.e., next/finish, back).
     * @return HtmlView
     */
    public function controller() : HtmlView {
        return $this->add(new HtmlView('controller'));
    }

    /**
     * Returns a protocol that is executed when the wizard is received.
     * @return ModifyProtocol|null
     */
    public abstract function getUpdateProtocol() : ?ModifyProtocol;
}
?>