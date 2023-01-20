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
        parent::__construct(array_merge(['alert'], $classes));

        $this->style(Tools::css('.alert:not(.screen)', '', 'padding: 15px',
            'margin-bottom: 20px', 'margin-top: 20px', 'border: 1px solid rgba(230, 230, 230, 1.0)',
            'border-radius: 4px', 'color: rgba(30, 30, 30, 1.0)', 'background-color: rgba(240, 240, 240, 1.0)'));
        $this->style(Tools::css('.alert.screen', [], 'display: flex', 'flex-direction: row',
            'align-items: center', 'justify-content: center', 'position: fixed', 'width: 100%', 'height: 100%',
            'top: 0', 'left: 0', 'z-index: 10010', 'background: none'));

        // For screen-size alerts.
        $this->style(Tools::css('.alert.screen', [' > ', '.overlay'], 'position: fixed',
            'top: 0', 'left: 0', 'width: 100%', 'height: 100%', 'background: rgba(0, 0, 0, 0.5)', 'z-index: 100011'));
        $this->style(Tools::css('.alert.screen', [' > ', '.a-message'], 'width: 75%', 'max-height: 75%',
            'background: rgba(240, 240, 240, 1.0)', 'z-index: 100012', 'padding: 20px',
            '-webkit-box-shadow: 3px 3px 10px 0px rgba(0,0,0,0.75)',
            '-moz-box-shadow: 3px 3px 10px 0px rgba(0,0,0,0.75)',
            'box-shadow: 3px 3px 10px 0px rgba(0,0,0,0.75)', 'position: relative'));

        $this->style(Tools::css('.alert.screen', [' > ', '.a-message', ' > ', '.closer'],
            'position: absolute', 'top: -15px', 'right: -15px', 'width: 30px', 'height: 30px',
            'z-index: 100013', 'background: rgba(255, 255, 255, 1.0)', 'border-radius: 45%',
            'justify-content: center', 'cursor: pointer', 'box-sizing: border-box',
            'border: 1px solid rgba(200, 200, 200, 1.0)'));
        $this->style(Tools::css('.alert.screen', [' > ', '.a-message', ' > ', '.closer', ':hover'],
            'background: rgba(200, 200, 200, 1.0);'));

        $this->style(Tools::css('.alert.screen', [' ', 'iframe'], 'width: 100%', 'border: 0',
            'background: rgba(255, 255, 255, 1.0)', 'border-radius: 10px', 'min-height: 250px',
            'max-height: 500px'));
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