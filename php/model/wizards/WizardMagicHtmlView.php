<?php

namespace webship\wizards;

use ship\interoperability\SLanguage as Language;
use ship\protocols\Protocol;
use webship\buttons\ButtonHtmlView;
use webship\buttons\CloserHtmlView;
use webship\controller\BasicEvents;
use webship\core\HtmlView;
use webship\interoperability\WEvent;
use webship\menus\ControlPanelHtmlView;
use webship\symbols\ImageHtmlView;
use webship\WebshipTools;

/**
 * View WizardMagicHtmlView.
 *
 * <p>A wizard manager view that stacks multiple multiple {@link WizardHtmlView} views,
 * step by step until the final wizard is closed.</p>
 *
 * <code>
 * $magic = WizardMagicHtmlView::create([$wizardStep1, $wizardStep2]);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/wizard.png" />
 * 
 * @title WizardMagic
 * @category wizards
 * 
 * @package webship
 * @subpackage php/model/wizards
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 *
 * @view
 * @examples
 *     $magic = WizardMagicHtmlView::create([$wizardStep1, $wizardStep2]);
 */
class WizardMagicHtmlView extends HtmlView {

    /**
     * Constructor.
     * @param string|string[] $classes Additional classes.
     */
    private function __construct(array|string $classes = []) {
        parent::__construct(array_merge(['magic'], is_array($classes) ? $classes : [$classes]));

        self::addCSS();

    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.magic', [], 'width: 100%', 'position: relative'));
        self::addStyle(WebshipTools::css('.magic', [' ', '.invisible'], 'display: none'));
        self::addStyle(WebshipTools::css('.magic', [' > ', '.ctr'], 'background: var(--ws-wizard-ctr-bg)',
            'border-radius: 0 0 0 5px'));  
    }

    /**
     * Creates a magic wizard view that rows one wizard after the other.
     * @param WizardHtmlView[] $wizards The different wizards, step by step.
     * @param Protocol|null $finishProtocol What happens if the final wizard is closed.
     * @return WizardMagicHtmlView
     */
    public static function create(array $wizards = [], ?Protocol $finishProtocol = null): WizardMagicHtmlView {
        $wizards = array_values($wizards);

        $view = new WizardMagicHtmlView();
        $view->ref('magic');
        $control = ControlPanelHtmlView::createUnbounded(false)->fix()->reverse();
        $view->add($control);
        $abortButton = $control->addMini(CloserHtmlView::create());
        $abortButton->protocol(WEvent::CLICK)->doIt(BasicEvents::Delete, [
            'view' => $view
        ], BasicEvents::environment())->
        append($finishProtocol);

        $view->each($wizards, function (WizardMagicHtmlView $view, WizardHtmlView $wizard, int $key) use ($wizards, $finishProtocol) {
            $view->add($wizard);
            $wizard->ref('wizard-' . $key);
            $controller = $wizard->controller();
            if ($key !== array_key_first($wizards)) {
                $wizard->addClass('invisible');
                $controller->add(new ButtonHtmlView('back-' . $wizard->id(), 'back-' . $wizard->id()))->
                    add(new ImageHtmlView('/webship/pic/back.svg'))->parent()->
                        texts()->
                            text(Language::ENGLISH, 'Back')->
                            text(Language::GERMAN, 'Zurück')->
                        parent()->
                        protocol(WEvent::CLICK)->
                            doIt(BasicEvents::ToggleClass, ['view' => $wizard, 'class' => 'invisible'], BasicEvents::environment())->
                            doIt(BasicEvents::ToggleClass, ['view' => 'wizard-' . ($key - 1), 'class' => 'invisible'], BasicEvents::environment())->
                            append($wizards[$key - 1]->getUpdateProtocol());
            }
            $nextButton = $controller->add(new ButtonHtmlView('next-' . $wizard->id(), 'next-' . $wizard->id()));
            $nextButton->texts()->
                text(Language::ENGLISH, $key !== array_key_last($wizards) ? 'Next' : 'Finish')->
                text(Language::GERMAN, $key !== array_key_last($wizards) ? 'Weiter' : 'Abschließen');
            $nextButton->add(new ImageHtmlView('/webship/pic/next.svg'));
            if ($key !== array_key_last($wizards)) {
                $nextButton->protocol(WEvent::CLICK)->
                    doIt(BasicEvents::ToggleClass, ['view' => $wizard, 'class' => 'invisible'], BasicEvents::environment())->
                    doIt(BasicEvents::ToggleClass, ['view' => 'wizard-' . ($key + 1), 'class' => 'invisible'], BasicEvents::environment())->
                    append($wizards[$key + 1]->getUpdateProtocol());
            } else {
                $nextButton->protocol(WEvent::CLICK)->doIt(BasicEvents::Delete, [
                    'view' => $view
                ], BasicEvents::environment())->
                    append($finishProtocol);
            }

        });
        return $view;
    }
}
?>