<?php

use ship\protocols\Protocol;
use webship\buttons\CloserHtmlView;
use webship\symbols\ImageHtmlView;

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

        $this->style(Tools::css('.magic', [], 'width: 100%', 'position: relative'));
        $this->style(Tools::css('.magic', [' ', '.invisible'], 'display: none'));
        $this->style(Tools::css('.magic', [' > ', '.ctr'], 'background: var(--neutral)',
            'border-radius: 0 0 0 5px'));
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
        $control = ControlPanelHtmlView::createUnbounded(false)->fix()->reverse();
        $view->add($control);
        $abortButton = $control->addMini(CloserHtmlView::create());
        $abortButton->protocol(Event::CLICK)->
            doIt('Delete', ['view' => $view], 'basics')->
            append($finishProtocol);

        $view->each($wizards, function (WizardMagicHtmlView $view, WizardHtmlView $wizard, int $key) use ($wizards, $finishProtocol) {
            $view->add($wizard);
            $wizard->ref('wizard-' . $key);
            $controller = $wizard->controller();
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