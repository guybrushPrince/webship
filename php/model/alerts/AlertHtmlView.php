<?php
/**
 * Class AlertHtmlView
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class AlertHtmlView extends HtmlView {

    /**
     * The box with the content.
     * @var HtmlView
     */
    private HtmlView $box;

    /**
     * AlertHtmlView constructor.
     * @param string|string[] $classes The classes.
     */
    public function __construct($classes = []) {
        parent::__construct(array_merge(['alert'], is_array($classes) ? $classes : [ $classes ]));

        $this->style(Tools::css('.alert:not(.cover)', '', 'padding: 15px',
            'margin-bottom: 20px', 'margin-top: 20px', 'border: 1px solid var(--light-system-background)',
            'border-radius: 4px', 'color: var(--font-dark)', 'background-color: var(--ultra-light-system-background)',
            'position: relative', 'box-sizing: border-box'));
        $this->style(Tools::css('.alert.cover', [], 'display: flex',
            'flex-direction: row', 'align-items: center', 'justify-content: center', 'width: 100%',
            'height: 100%', 'top: 0', 'left: 0', 'z-index: 10010', 'background: none'));
        $this->style(Tools::css('.alert.screen', [], 'position: fixed'));
        $this->style(Tools::css('.alert.full', [], 'position: absolute'));

        // For screen-size alerts.
        $this->style(Tools::css('.alert.cover', [' > ', '.overlay'], 'top: 0', 'left: 0',
            'width: 100%', 'height: 100%', 'background: var(--window-overlay)', 'z-index: 100011'));
        $this->style(Tools::css('.alert.screen', [' > ', '.overlay'], 'position: fixed'));
        $this->style(Tools::css('.alert.full', [' > ', '.overlay'], 'position: absolute'));
        $this->style(Tools::css('.alert.cover', [' > ', '.a-message'], 'width: 75%', 'max-height: 75%',
            'background: var(--ultra-light-system-background)', 'z-index: 100012', 'padding: 20px',
            '-webkit-box-shadow: var(--shadow-flow-4)',
            '-moz-box-shadow: var(--shadow-flow-4)',
            'box-shadow: var(--shadow-flow-4)', 'position: relative'));

        $this->style(Tools::css('.alert.cover', [' > ', '.a-message', ' > ', '.closer'],
            'position: absolute', 'top: -15px', 'right: -15px', 'width: 30px', 'height: 30px',
            'z-index: 100013', 'background: var(--neutral)', 'border-radius: 45%',
            'justify-content: center', 'cursor: pointer', 'box-sizing: border-box',
            'border: 1px solid var(--system-background)'));
        $this->style(Tools::css('.alert.cover', [' > ', '.a-message', ' > ', '.closer', ':hover'],
            'background: var(--system-background);'));

        $this->style(Tools::css('.alert.cover', [' ', 'iframe'], 'width: 100%', 'border: 0',
            'background: var(--neutral)', 'border-radius: 10px', 'min-height: 250px',
            'max-height: 500px'));

        $this->style(Tools::css('.alert.cover', [' > ', '.a-message', ' > ', 'div:last-child'],
            'display: flex', 'flex-direction: column', 'align-items: center'));

        $this->style(Tools::css('.alert', [' ', 'pre'], 'display: block'));
        $this->style(Tools::css('.alert', [' ', 'pre', ' > ', 'code'], 'width: 100%',
            'border-radius: 10px', 'max-height: 200px', 'overflow: auto', 'overflow: auto'));
        $this->style(Tools::css('.alert', [' ', 'code'], 'border: 0',
            'background: var(--neutral)', 'font-family: Monospace', 'font-size: 14px', 'padding: 5px',
            'display: inline-block', 'border-radius: 3px'));
        $this->style(Tools::css('.alert.cover', [' ', 'code'], 'display: block', 'height: 400px',
            'font-size: 10px', 'padding: inherit', 'min-height: 100px', 'max-height: 50%'));

        $this->style(Tools::css('.alert.invisible', [], 'display: none'));
        $this->style(Tools::css('.alert', [' > ', '.closer'], 'position: absolute', 'top: 3px',
            'right: 5px'));
        $this->style(Tools::css('.alert:not(.cover).with-image', [], 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'gap: 15px'));
        $this->style(Tools::css('.alert:not(.cover).with-image', [' > ', 'img'], 'width: 25px'));
        $this->style(Tools::css('.alert.cover', [' > ', '.a-message.with-image'], 'display: flex',
            'flex-direction: row', 'flex-wrap: nowrap', 'gap: 15px'));
        $this->style(Tools::css('.alert.cover', [' > ', '.a-message.with-image', ' > ', 'img'],
            'width: 25px'));

        $this->style(Tools::css('.alert.inline', [], 'margin: 0', 'padding: 0 15px'));
    }

    /**
     * Creates an alert html view.
     * @param string[] $message The multilingual messages.
     * @param bool $screen If it should cover the screen.
     * @param bool $fullView If it should cover the view.
     * @param AlertHtmlView|null $v A view that is recycled.
     * @param string|null $property A property of the user context, which shall be set.
     * @param string|null $image An image to be shown on the left.
     * @return AlertHtmlView
     * @throws DatabaseError
     */
    public static function create(array $message = [], bool $screen = false, bool $fullView = false,
                                  ?AlertHtmlView $v = null, ?string $property = null,
                                  ?string $image = null) : AlertHtmlView {
        if ($property !== null) {
            $show = BreakwaterUtil::getUserParameter($property, Type::BOOLEAN, true);
            if (!$show) {
                if (!$v) return new AlertHtmlView('invisible');
                else {
                    $v->addClass('invisible');
                    return $v;
                }
            }
        }

        if (!$v) $v = new AlertHtmlView($screen ? ['cover', 'screen'] : ($fullView ? ['cover', 'full'] : []));
        else {
            if ($screen || $fullView) $v->addClass('cover');
            if ($screen) $v->addClass('screen');
            if ($fullView) $v->addClass('full');
        }
        $con = $v;
        if ($screen || $fullView) {
            $v->ref('alert');
            $overlay = $v->add(new HtmlView('overlay'));
            $overlay->protocol(Event::CLICK)->doIt('Delete', ['view' => $v], 'basics');
            $con = $v->add(new HtmlView('a-message'));
            $con->ref('alert-content');
            $control = ControlPanelHtmlView::create(1, 1, false)->fix()->plain();
            $con->add($control);
            $closer = $control->add(new CloserHtmlView());
            $closer->protocol(Event::CLICK)->doIt('Delete', ['view' => $v], 'basics');
        }
        $v->box = $con;
        if (/*!$screen && */$image) {
            $con->addClass('with-image');
            $con->add(new ImageHtmlView($image));
            $textView = $con->add(new HtmlView());
            $v->box = $textView;
        } else $textView = $con;
        $textView->texts()->text(self::getLanguage(), (array_key_exists(self::getLanguage(), $message) ?
            ParsedownExtra::instance()->text($message[self::getLanguage()]) : ''));



        if (!$screen && !$fullView && $property !== null) {
            $con->ref('alert-view');
            $control = ControlPanelHtmlView::create(1, 1, false)->fix()->plain();
            $con->add($control);
            $closer = $control->add(new CloserHtmlView());
            $closer->notice(Language::ENGLISH, 'Do not show this hint anymore.');
            $closer->notice(Language::GERMAN, 'Diesen Hinweis nicht mehr anzeigen.');
            $closer->protocol(Event::CLICK)->
                doIt('ChangeParameter', [
                    'target' => BreakwaterUtil::getUserConfiguration(BreakwaterUtil::getCurrentUser()),
                    'parameter' => $property,
                    'type' => Type::BOOLEAN,
                    'value' => false
                ], 'texts')->
                doIt('Delete', ['view' => $v], 'basics');
        }

        return $v;
    }

    /**
     * Get the box.
     * @return HtmlView
     */
    public function box() : HtmlView {
        return $this->box;
    }

    /**
     * Creates an inline alert.
     * @return $this
     */
    public function inline() : static {
        $this->addClass('inline');
        return $this;
    }
}
?>