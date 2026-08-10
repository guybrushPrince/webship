<?php

namespace webship\containers;

use webship\controller\BasicEvents;
use webship\interoperability\WEvent;
use webship\core\HtmlView;
use webship\tabs\TabContainerHtmlView;
use webship\WebshipTools;

/**
 * View EditorSplitHtmlView.
 *
 * <p>An editor view that separates the available space into three areas: a tool area, a separator, and an editor.</p>
 *
 * @package webship
 * @subpackge php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class EditorSplitHtmlView extends HtmlView {

    /**
     * The tools section.
     * @var TabContainerHtmlView
     */
    private TabContainerHtmlView $tools;

    /**
     * The editor section.
     * @var TabContainerHtmlView
     */
    private TabContainerHtmlView $editor;

    /**
     * Constructor.
     * @param int|null $editorSize The size of the editor.
     * @param string[]|string $classes Some classes.
     */
    protected function __construct(?int $editorSize = null, array|string $classes = []) {
        if (!is_array($classes)) $classes = [$classes];
        $classes[] = 'editor-split';
        parent::__construct($classes);

        if (!$editorSize) $editorSize = 75;
        $toolsSize = 100 - $editorSize;
        $this->localStyle('--ws-editor-size: calc(' . $editorSize . '% - var(--ws-sep-size))');
        $this->localStyle('--ws-tools-size: ' . $toolsSize . '%');

        $this->tools = TabContainerHtmlView::create(['tools']);
        $this->add($this->tools);
        $separator = new HtmlView('separator');
        $this->add($separator);
        $this->editor = TabContainerHtmlView::create(['editor']);
        $this->add($this->editor);

        $separator->protocol(WEvent::CLICK)->
            doIt(BasicEvents::ToggleClass, ['view' => $this->tools, 'class' => 'closed'], BasicEvents::environment());

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.editor-split', [], '--ws-sep-size: 15px', 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'width: 100%', 'height: 100%',
            'border-top: 2px solid var(--ws-app-brd)'));
        self::addStyle(WebshipTools::css('.editor-split', [' ', '.tab'],
            'padding: 0', 'height: calc(100% - var(--ws-tab-hd-hgt))', 'border-left: 2px solid var(--ws-app-brd)',
            'border-right: 2px solid var(--ws-app-brd)', 'border-bottom: 2px solid var(--ws-app-brd)'));
        self::addStyle(WebshipTools::css('.editor-split', [' ', '.tab', ' > ', '.tab-content'],
            'padding: 10px', 'width: 100%', 'height: 100%', 'scroll-padding-top: calc(var(--ws-tab-hd-hgt) - 2px)',
            'scroll-behavior: smooth'));
        self::addStyle(WebshipTools::css('.editor-split', [' > ', '*'],
            'box-sizing: border-box', 'display: flex', 'flex-direction: column', 'overflow-y: hidden'));
        self::addStyle(WebshipTools::css('.editor-split', [' > ', '.tools'], 'width: var(--ws-tools-size)',
            'padding: var(--ws-tab-hd-hgt) 10px 10px 10px', 'transition: width 0.5s'));
        self::addStyle(WebshipTools::css('.editor-split', [' > ', '.tools.closed'], 'width: 0', 'padding: 0'));
        self::addStyle(WebshipTools::css('.editor-split', [' > ', '.tools.closed', ' > ', '*'],
            'display: none'));
        self::addStyle(WebshipTools::css('.editor-split', [' > ', '.separator'], 'width: var(--ws-sep-size)',
            'cursor: pointer', 'transition: 0.5s', 'margin: 0', 'border-radius: 0', 'background: var(--ws-main-menu)'));
        self::addStyle(WebshipTools::css('.editor-split', [' > ', '.separator', ':hover'],
            'background: var(--ws-main-menu-hvr)'));
        self::addStyle(WebshipTools::css('.editor-split', [' > ', '.separator', ':before'],
            'content: \'<\'', 'display: flex', 'width: 100%', 'height: 100%',
            'flex-direction: row', 'align-items: center', 'justify-content: center', 'font-weight: bold'));
        self::addStyle(WebshipTools::css('.editor-split', [' > ', '.tools.closed', ' + ', '.separator', ':before'],
            'content: \'>\''));
        self::addStyle(WebshipTools::css('.editor-split', [' > ', '.editor'], 'width: var(--ws-editor-size)',
            'padding: var(--ws-tab-hd-hgt) 15px 15px 15px', 'transition: width 0.5s'));
        self::addStyle(WebshipTools::css('.editor-split', [' > ', '.tools.closed', ' ~ ', '.editor'],
            'width: calc(100% - var(--ws-sep-size))'));
    }

    /**
     * Get the tools section.
     * @return TabContainerHtmlView
     */
    public function getTools() : TabContainerHtmlView {
        return $this->tools;
    }

    /**
     * Get the editor section.
     * @return TabContainerHtmlView
     */
    public function getEditor() : TabContainerHtmlView {
        return $this->editor;
    }
}
?>