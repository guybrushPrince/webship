<?php
/**
 * Class AlertHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class AlertHtmlView extends HtmlView {

    /**
     * AlertHtmlView constructor.
     * @param string|string[] $classes The classes.
     */
    public function __construct($classes = []) {
        parent::__construct(array_merge(['alert'], is_array($classes) ? $classes : [ $classes ]));

        $this->style(Tools::css('.alert:not(.screen)', '', 'padding: 15px',
            'margin-bottom: 20px', 'margin-top: 20px', 'border: 1px solid var(--light-system-background)',
            'border-radius: 4px', 'color: var(--font-dark)', 'background-color: var(--ultra-light-system-background)'));
        $this->style(Tools::css('.alert.screen', [], 'display: flex', 'flex-direction: row',
            'align-items: center', 'justify-content: center', 'position: fixed', 'width: 100%', 'height: 100%',
            'top: 0', 'left: 0', 'z-index: 10010', 'background: none'));

        // For screen-size alerts.
        $this->style(Tools::css('.alert.screen', [' > ', '.overlay'], 'position: fixed',
            'top: 0', 'left: 0', 'width: 100%', 'height: 100%', 'background: var(--window-overlay)', 'z-index: 100011'));
        $this->style(Tools::css('.alert.screen', [' > ', '.a-message'], 'width: 75%', 'max-height: 75%',
            'background: var(--ultra-light-system-background)', 'z-index: 100012', 'padding: 20px',
            '-webkit-box-shadow: var(--shadow-flow-4)',
            '-moz-box-shadow: var(--shadow-flow-4)',
            'box-shadow: var(--shadow-flow-4)', 'position: relative'));

        $this->style(Tools::css('.alert.screen', [' > ', '.a-message', ' > ', '.closer'],
            'position: absolute', 'top: -15px', 'right: -15px', 'width: 30px', 'height: 30px',
            'z-index: 100013', 'background: var(--neutral)', 'border-radius: 45%',
            'justify-content: center', 'cursor: pointer', 'box-sizing: border-box',
            'border: 1px solid var(--system-background)'));
        $this->style(Tools::css('.alert.screen', [' > ', '.a-message', ' > ', '.closer', ':hover'],
            'background: var(--system-background);'));

        $this->style(Tools::css('.alert.screen', [' ', 'iframe'], 'width: 100%', 'border: 0',
            'background: var(--neutral)', 'border-radius: 10px', 'min-height: 250px',
            'max-height: 500px'));

        $this->style(Tools::css('.alert', [' ', 'code'], 'width: 100%', 'border: 0',
            'background: var(--neutral)', 'border-radius: 10px', 'min-height: 100px',
            'max-height: 50%', 'overflow: auto', 'font-family: Monospace', 'font-size: 10px',
            'display: block', 'height: 400px'));
    }

    /**
     * Creates a alert html view.
     * @param string[] $message The multilingual messages.
     * @param bool $screen If it should cover the screen.
     * @param AlertHtmlView|null $v A view that is recycled.
     * @return AlertHtmlView
     */
    public static function create(array $message = [], bool $screen = false, ?AlertHtmlView $v = null) : AlertHtmlView {
        if (!$v) $v = new AlertHtmlView($screen ? [] : ['screen']);
        else if ($screen) $v->addClass('screen');
        $con = $v;
        if ($screen) {
            $v->ref('alert');
            $overlay = $v->add(new HtmlView('overlay'));
            $overlay->protocol(Event::CLICK)->doIt('Delete', ['view' => $v], 'basics');
            $con = $v->add(new HtmlView('a-message'));
            $con->ref('alert-content');
            $closer = new CloserHtmlView();
            $con->add($closer);
            $closer->protocol(Event::CLICK)->doIt('Delete', ['view' => $v], 'basics');
        }
        $con->texts()->text(self::getLanguage(), (array_key_exists(self::getLanguage(), $message) ?
            $message[self::getLanguage()] : ''));

        return $v;
    }
}
?>