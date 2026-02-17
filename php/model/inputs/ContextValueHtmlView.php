<?php

namespace webship\inputs;

use Exception;
use HoldFile;
use progression\ContextSerializer;
use progression\ContextVariable;
use progression\IProgressionSerializable;
use progression\NotImplementedException;
use progression\SimplePersistence;
use progression\ui\Representer;
use ship\views\TextView;
use ship\views\View;
use Tools;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View ContextValueHtmlView.
 *
 * <p>Visualizes values within arrays, objects, as well as classes in a user comprehensible way.</p>
 *
 * <code>
 * ContextValueHtmlView::buildContext('Hello World')
 * ContextValueHtmlView::buildContext([1,2,3,4,5])
 * ContextValueHtmlView::buildContext(Project::get(3714510425571326813))
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/contextvalue.png" />
 *
 * @package webship
 * @subpackge php/model/inputs
 *
 * @version 1.1.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 *
 * @view
 *
 * @examples
 *     ContextValueHtmlView::buildContext('Hello World')
 *     ContextValueHtmlView::buildContext([1,2,3,4,5])
 *     ContextValueHtmlView::buildContext(Project::get(3714510425571326813))
 */
class ContextValueHtmlView extends HtmlView {

    /**
     * A private container for adding things (sub values, etc.)
     * @var HtmlView|null
     */
    private ?HtmlView $container = null;

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'context-value';
        parent::__construct($classes);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('.context-value', [], '--info-gap: 5px', 'display: flex',
            'gap: var(--info-gap)', 'flex-direction: row'));
        self::addStyle(WebshipTools::css('.context-value.scalar', [], 'align-items: stretch',
            'border: 1px solid var(--color)', 'width: fit-content', 'width: -moz-fit-content',
            'padding-right: 3px', 'border-radius: 3px', 'color: var(--color)'));
        self::addStyle(WebshipTools::css('.context-value.scalar', [':before'], 'content: var(--type)',
            'background: var(--color)', 'color: var(--font)', 'padding: 2px', 'display: flex', 'flex-direction: row',
            'align-items: center'));
        self::addStyle(WebshipTools::css('.context-value.scalar', [' > ', 'div:first-child'],
            'display: flex', 'flex-direction: row', 'align-items: center'));
        self::addStyle(WebshipTools::css('.context-value.scalar.con-variable', [], 'padding-right: 0'));
        self::addStyle(WebshipTools::css('.context-value.scalar.con-variable', [':after'], 'content: "}}"',
            'background: var(--color)', 'color: var(--font)', 'padding: 2px', 'display: flex', 'flex-direction: row',
            'align-items: center'));

        self::addStyle(WebshipTools::css('.context-value.array', [], 'justify-content: stretch',
            'min-height: 15px', '--parenthesis: 2px'));
        self::addStyle(WebshipTools::css('.context-value.array', [':before'], 'content: ""', 'display: block',
            'width: var(--parenthesis)', 'border: var(--parenthesis) solid #000', 'border-right: none',
            'box-sizing: content-box'));
        self::addStyle(WebshipTools::css('.context-value.array', [' > ', 'div:first-child'], 'display: flex',
            'flex-direction: row', 'flex-wrap: wrap', 'align-items: center', 'gap: var(--info-gap)',
            'max-width: calc(100% - (4 * var(--parenthesis) + 2 * var(--info-gap)))'));
        self::addStyle(WebshipTools::css('.context-value.array', [':after'], 'content: ""', 'display: block',
            'width: var(--parenthesis)', 'border: var(--parenthesis) solid #000', 'border-left: none',
            'box-sizing: content-box'));

        self::addStyle(WebshipTools::css('.context-value.jobject', [], 'justify-content: stretch',
            'min-height: 15px', '--parenthesis: 2px', 'align-items: stretch', 'gap: 0'));
        self::addStyle(WebshipTools::css('.context-value.jobject', [' > ', 'div:first-child'], 'display: flex',
            'flex-direction: row', 'flex-wrap: wrap', 'align-items: center', 'width: calc(2 * var(--parenthesis))'));
        self::addStyle(WebshipTools::css('.context-value.jobject', [' > ', 'div:first-child:before'],
            'content: ""', 'display: inline-block',
            'height: var(--parenthesis)', 'width: calc(2 * var(--parenthesis))', 'background: #000'));
        self::addStyle(WebshipTools::css('.context-value.jobject', [' > ', 'div:nth-child(2)'],
            'width: calc(2 * var(--parenthesis))', 'border: var(--parenthesis) solid #000',
            'border-radius: 10px', 'border-right: none', 'margin-right: var(--info-gap)'));
        self::addStyle(WebshipTools::css('.context-value.jobject', [' > ', 'div:nth-child(3)'], 'display: flex',
            'flex-direction: row', 'flex-wrap: wrap', 'align-items: center', 'gap: var(--info-gap)',
            'max-width: calc(100% - (20 * var(--parenthesis) - 2 * var(--info-gap)))'));
        self::addStyle(WebshipTools::css('.context-value.jobject', [' > ', 'div:nth-child(4)'],
            'width: calc(2 * var(--parenthesis))', 'border: var(--parenthesis) solid #000',
            'border-radius: 10px', 'border-left: none', 'margin-left: var(--info-gap)'));
        self::addStyle(WebshipTools::css('.context-value.jobject', [' > ', 'div:last-child'], 'display: flex',
            'flex-direction: row', 'flex-wrap: wrap', 'align-items: center', 'width: calc(2 * var(--parenthesis))'));
        self::addStyle(WebshipTools::css('.context-value.jobject', [' > ', 'div:last-child:after'],
            'content: ""', 'display: inline-block',
            'height: var(--parenthesis)', 'width: calc(2 * var(--parenthesis))', 'background: #000'));
        self::addStyle(WebshipTools::css('.context-value.wrap', [], 'flex-wrap: wrap'));
    }

    /**
     * Create a context value.
     * @param string $type The type of the context value.
     * @param string[]|string $classes Some classes.
     * @return ContextValueHtmlView
     */
    private static function create(string $type, array|string $classes = []) : ContextValueHtmlView {
        $view = new ContextValueHtmlView($classes);
        if ($type !== 'array' && $type !== 'jobject') {
            $container = new HtmlView();
            $view->add($container);
            $view->container = $container;
            $view->addClass('scalar');
            $color = Tools::textToColor($type);
            $view->localStyle('--color: ' . $color);
            $view->localStyle('--font: ' . Tools::getFontColor($color));
            if ($type !== 'con-variable') {
                $view->localStyle('--type: "' . $type . '"');
            } else {
                $view->addClass('con-variable');
                $view->localStyle('--type: "{{"');
            }
        } else if ($type === 'array') {
            $container = new HtmlView();
            $view->add($container);
            $view->container = $container;
            $view->addClass('array');
            $view->wrap();
        } else {
            $view->add(new HtmlView());
            $view->add(new HtmlView());
            $container = new HtmlView();
            $view->add($container);
            $view->add(new HtmlView());
            $view->add(new HtmlView());
            $view->container = $container;
            $view->addClass('jobject');
            $view->wrap();
        }
        return $view;
    }

    /**
     * @inheritDoc
     */
    public function add(View $view) : View {
        if (!$this->container) {
            return parent::add($view);
        } else {
            $this->container->add($view);
        }
        return $view;
    }

    /**
     * @inheritDoc
     */
    public function texts(array|string|null $texts = null) : HtmlView|TextView {
        $this->container->texts($texts);
        return $this->container;
    }

    /**
     * Add a key-value pair.
     * @param string $key The key.
     * @param View $value The value view.
     * @return void
     */
    public function keyValue(string $key, View $value) : void {
        $view = ContextValueHtmlView::create($key);
        $view->localStyle('--color: var(--system-background)');
        $view->add($value);
        $this->add($view);
    }

    /**
     * Builds a context value (or a more complex structure).
     * @param string|bool|int|float|ContextVariable|IProgressionSerializable|array|null $value The value.
     * @param string[]|string $classes Some classes.
     * @return ContextValueHtmlView
     * @throws NotImplementedException
     * @throws Exception
     */
    public static function buildContext(string|bool|int|float|ContextVariable|IProgressionSerializable|array|null $value,
                                        array|string $classes = []) : ContextValueHtmlView {
        if ($value instanceof IProgressionSerializable) {
            $view = ContextValueHtmlView::create('object', $classes);
            $isVisualRepresented = Representer::hasVisualRepresentation($value->getPermanentClass());
            if ($isVisualRepresented) {
                SimplePersistence::instance()->unsafeMode();
                $obj = ContextSerializer::deserializeObject($value->getPermanentId(), $value->getPermanentClass());
                $withFunctionality = false;
                if ($obj instanceof HoldFile) $withFunctionality = true;
                $rep = Representer::getVisualRepresentation($obj, View::getLanguage(), $withFunctionality);
                $view->add($rep);
            } else {
                $view->texts($value->getPermanentClass() . $value->getPermanentId());
            }
            return $view;
        } else if ($value instanceof ContextVariable) {
            $view = ContextValueHtmlView::create('con-variable', $classes);
            $view->texts($value->getName());
            return $view;
        } else if (is_int($value)) {
            $view = ContextValueHtmlView::create('int', $classes);
            $view->texts($value);
            return $view;
        } else if (is_bool($value)) {
            $view = ContextValueHtmlView::create('bool', $classes);
            $view->texts($value ? 'true' : 'false');
            return $view;
        } else if (is_string($value)) {
            $view = ContextValueHtmlView::create('string', $classes);
            $view->texts($value);
            return $view;
        } else if (is_float($value)) {
            $view = ContextValueHtmlView::create('float', $classes);
            $view->texts($value);
            return $view;
        } else if (is_null($value)) {
            $view = ContextValueHtmlView::create('null', $classes);
            $view->texts('NULL');
            return $view;
        } else {
            // It is an array
            if (WebshipTools::hasStringKeys($value)) {
                return ContextValueHtmlView::create('jobject', $classes)->
                    each($value, function (ContextValueHtmlView $v, $val, string $key) {
                        $v->keyValue($key, self::buildContext($val));
                    });
            } else return ContextValueHtmlView::create('array', $classes)->
                each($value, function (ContextValueHtmlView $v, $val) {
                    $v->add(self::buildContext($val));
                });
        }
    }

    /**
     * Set overflow.
     * @return $this
     */
    public function wrap() : static {
        $this->addClass('wrap');
        return $this;
    }
}
?>