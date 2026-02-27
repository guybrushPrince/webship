<?php

namespace webship\wizards;

use ship\protocols\ModifyProtocol;
use webship\containers\ContainerHtmlView;
use webship\core\HtmlView;
use webship\symbols\ImageHtmlView;
use webship\WebshipTools;

/**
 * Class WizardHtmlView.
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
abstract class WizardHtmlView extends HtmlView {

    /**
     * The title view.
     * @var ContainerHtmlView|null
     */
    private ?ContainerHtmlView $title = null;

    /**
     * The explanation view.
     * @var ContainerHtmlView|null
     */
    private ?ContainerHtmlView $explanation = null;

    /**
     * The active view.
     * @var ContainerHtmlView|null
     */
    private ?ContainerHtmlView $active = null;

    /**
     * The controller view.
     * @var HtmlView|null
     */
    private ?HtmlView $controller = null;

    /**
     * Constructor.
     * @param string|string[] $classes Additional classes.
     */
    public function __construct(array|string $classes = []) {
        parent::__construct(array_merge(['wizard'], is_array($classes) ? $classes : [$classes]));

        $this->style(WebshipTools::css('.wizard', [], 'width: 100%', 'display: flex',
            'flex-direction: column', 'flex-wrap: wrap', 'position: relative',
            '-webkit-transition: -webkit-box-shadow .25s',
            'transition: box-shadow .25s', 'border-radius: 2px',
            '-webkit-box-shadow: var(--ws-haptic-shadow)',
            'box-shadow: var(--ws-haptic-shadow)'));
        $this->style(WebshipTools::css('.wizard', [':hover'],
            '-webkit-box-shadow: var(--ws-haptic-fc-shadow)',
            'box-shadow: var(--ws-haptic-fc-shadow)'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.title'], 
            'padding: 24px', 'font-size: 24px',
            'font-weight: 300', 'box-sizing: border-box', 'background: var(--ws-wizard-ttl-bg)',
            'color: var(--ws-wizard-ttl-font)',
            'border-bottom: solid 1px var(--ws-wizard-ttl-brd)'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.title', ' > ', 'img'], 'height: 20px',
            'filter: var(--ws-wizard-ttl-img-fltr)'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.explanation'], 'padding: 20px',
            'background: var(--ws-wizard-expl-bg)'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.active'], 'padding: 20px',
            'background: var(--ws-wizard-actv-bg)'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.active', ' ', '.tiles'], 'border: 0'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.controller'], 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'flex: 1 0 0'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.controller', ' ', '.button'], 'display: flex',
            'flex-direction: row', 'align-items: center', 'justify-content: center', 'cursor: pointer', 'border: 0',
            'transition: 0.5s background'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.controller', ' ', '.button.abort'],
            'width: 50px', 'background: var(--ws-wizard-abort)'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.controller', ' ', '.button.abort', ':hover'],
            'background: var(--ws-wizard-abort-hvr) !important'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.controller', ' ', '.button', ' > ', 'img'],
            'height: 20px', 'margin-left: 10px'));
        $this->style(WebshipTools::css('.wizard', [' > ', '.controller', ' ', '.button.abort', ' > ', 'img'],
            'height: 20px', 'margin: 15px'));
    }

    /**
     * Create a title.
     * @return ContainerHtmlView
     */
    public function title() : ContainerHtmlView {
        if ($this->title) return $this->title;
        $this->title = new ContainerHtmlView(['hor', 'w100', 'vcent', 'gap20', 'title']);
        $this->add($this->title);
        $this->title->add(new ImageHtmlView('/webship/pic/wand.svg', 'wand'));
        return $this->title;
    }

    /**
     * Create an explanation.
     * @return ContainerHtmlView
     */
    public function explanation() : ContainerHtmlView {
        if ($this->explanation) return $this->explanation;
        $this->explanation = new ContainerHtmlView('explanation');
        $this->add($this->explanation);
        return $this->explanation;
    }

    /**
     * Create the area for active content (where the user must do something).
     * @return ContainerHtmlView
     */
    public function active() : ContainerHtmlView {
        if ($this->active) return $this->active;
        $this->active = new ContainerHtmlView('active');
        $this->add($this->active);
        return $this->active;
    }

    /**
     * Create the area for controlling the wizard (i.e., next/finish, back).
     * @return HtmlView
     */
    public function controller() : HtmlView {
        if ($this->controller) return $this->controller;
        $this->controller = new HtmlView('controller');
        $this->add($this->controller);
        return $this->controller;
    }

    /**
     * Returns a protocol that is executed when the wizard is received.
     * @return ModifyProtocol|null
     */
    public abstract function getUpdateProtocol() : ?ModifyProtocol;
}
?>