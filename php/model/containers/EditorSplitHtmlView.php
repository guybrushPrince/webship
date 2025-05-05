<?php

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
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'editor-split';
        parent::__construct($classes);

        if (!$editorSize) $editorSize = 75;
        $toolsSize = 100 - $editorSize;
        $this->localStyle('--editor-size: calc(' . $editorSize . '% - var(--separator-size))');
        $this->localStyle('--tools-size: ' . $toolsSize . '%');

        $this->tools = new TabContainerHtmlView('tools');
        $this->add($this->tools);
        $separator = new HtmlView('separator');
        $this->add($separator);
        $this->editor = new TabContainerHtmlView('editor');
        $this->add($this->editor);

        $separator->protocol(Event::CLICK)->
            doIt('Toggle', ['view' => $this->tools, 'class' => 'closed'], 'reports');

        self::addCSS();
    }

    /**
     * Generates the CSS code.
     * @return void
     */
    public static function addCSS() : void {
        self::addStyle(Tools::css('.editor-split', [], '--separator-size: 15px', 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'width: 100%', 'height: 100%',
            'border-top: 2px solid var(--login-border)'));
        self::addStyle(Tools::css('.editor-split', [' ', '.tab'],
            'padding: 0', 'height: calc(100% - 27px)', 'border-left: 2px solid var(--login-border)',
            'border-right: 2px solid var(--login-border)', 'border-bottom: 2px solid var(--login-border)'));
        self::addStyle(Tools::css('.editor-split', [' ', '.tab', ' > ', '.tab-content'],
            'padding: 10px', 'width: 100%', 'height: 100%'));
        self::addStyle(Tools::css('.editor-split', [' > ', '*'],
            'box-sizing: border-box', 'display: flex', 'flex-direction: column', 'overflow-y: hidden'));
        self::addStyle(Tools::css('.editor-split', [' > ', '.tools'], 'width: var(--tools-size)',
            'padding: 10px', 'transition: width 0.5s'));
        self::addStyle(Tools::css('.editor-split', [' > ', '.tools.closed'], 'width: 0', 'padding: 0'));
        self::addStyle(Tools::css('.editor-split', [' > ', '.tools.closed', ' > ', '*'],
            'display: none'));
        self::addStyle(Tools::css('.editor-split', [' > ', '.separator'], 'width: var(--separator-size)',
            'cursor: pointer', 'transition: 0.5s', 'margin: 0', 'border-radius: 0', 'background: var(--main-menu)'));
        self::addStyle(Tools::css('.editor-split', [' > ', '.separator', ':hover'],
            'background: var(--main-menu-hover)'));
        self::addStyle(Tools::css('.editor-split', [' > ', '.separator', ':before'],
            'content: \'<\'', 'display: flex', 'width: 100%', 'height: 100%',
            'flex-direction: row', 'align-items: center', 'justify-content: center', 'font-weight: bold'));
        self::addStyle(Tools::css('.editor-split', [' > ', '.tools.closed', ' + ', '.separator', ':before'],
            'content: \'>\''));
        self::addStyle(Tools::css('.editor-split', [' > ', '.editor'], 'width: var(--editor-size)',
            'padding: 15px', 'transition: width 0.5s'));
        self::addStyle(Tools::css('.editor-split', [' > ', '.tools.closed', ' ~ ', '.editor'],
            'width: calc(100% - var(--separator-size))'));
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