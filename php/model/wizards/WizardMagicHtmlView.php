<?php

/**
 * Class WizardMagicHtmlView.
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class WizardMagicHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Additional classes.
     */
    public function __construct(array|string $classes = []) {
        parent::__construct(array_merge(['magic'], is_array($classes) ? $classes : [ $classes ]));

        $this->style(Tools::css('.magic', [], 'max-width: 800px', 'position: relative'));
        $this->style(Tools::css('.magic', [' > ', 'img.wand'], 'position: absolute',
            'top: 5px', 'right: 5px', 'width: 30px', 'z-index: 1'));
        $this->style(Tools::css('.magic', [' ', '.invisible'], 'display: none'));
    }

    /**
     * Creates a magic wizard view that rows one wizard after the other.
     * @param WizardHtmlView[] $wizards The different wizards, step by step.
     * @param Protocol|null $finishProtocol What happens if the final wizard is closed.
     * @return WizardMagicHtmlView
     */
    public static function create(array $wizards = [], ?Protocol $finishProtocol = null) : WizardMagicHtmlView {
        $wizards = array_values($wizards);

        $view = new WizardMagicHtmlView();
        $view->ref('magic');
        $view->add(new ImageHtmlView('/webship/pic/wand.svg', 'wand'));
        $view->each($wizards, function (WizardMagicHtmlView $view, WizardHtmlView $wizard, int $key) use ($wizards, $finishProtocol) {
            $view->add($wizard);
            $wizard->ref('wizard-' . $key);
            $controller = $wizard->controller();
            if ($key !== array_key_last($wizards)) {
                $abortButton = $controller->add(new ButtonHtmlView('abort-' . $wizard->id(), 'abort-' . $wizard->id(), 'abort'));
                $abortButton->add(new ImageHtmlView('/sunstone/pic/wrong.svg'));
                $abortButton->protocol(Event::CLICK)->
                    doIt('Delete', ['view' => $view], 'basics')->
                    append($finishProtocol);
            }
            if ($key !== array_key_first($wizards)) {
                $wizard->addClass('invisible');
                $controller->add(new ButtonHtmlView('back-' . $wizard->id(), 'back-' . $wizard->id()))->
                    add(new ImageHtmlView('/sunstone/pic/back.svg'))->parent()->
                    texts()->
                        text(Language::ENGLISH, 'Back')->
                        text(Language::GERMAN,  'Zurück')->
                    parent()->
                    protocol(Event::CLICK)->
                        doIt('ToggleClass', ['view' => $wizard, 'class' => 'invisible'], 'basics')->
                        doIt('ToggleClass', ['view' => 'wizard-' . ($key - 1), 'class' => 'invisible'], 'basics')->
                        append($wizards[$key - 1]->getUpdateProtocol())->
                    parent();
            }
            $nextButton = $controller->add(new ButtonHtmlView('next-' . $wizard->id(), 'next-' . $wizard->id()));
            $nextButton->texts()->
                text(Language::ENGLISH, $key !== array_key_last($wizards) ? 'Next' : 'Finish')->
                text(Language::GERMAN,  $key !== array_key_last($wizards) ? 'Weiter' : 'Abschließen');
            $nextButton->add(new ImageHtmlView('/sunstone/pic/next.svg'));
            if ($key !== array_key_last($wizards)) {
                $nextButton->protocol(Event::CLICK)->
                    doIt('ToggleClass', ['view' => $wizard, 'class' => 'invisible'], 'basics')->
                    doIt('ToggleClass', ['view' => 'wizard-' . ($key + 1), 'class' => 'invisible'], 'basics')->
                    append($wizards[$key + 1]->getUpdateProtocol());
            } else {
                $nextButton->protocol(Event::CLICK)->
                    doIt('Delete', ['view' => $view], 'basics')->
                    append($finishProtocol);
            }

        });
        return $view;
    }
}
?>