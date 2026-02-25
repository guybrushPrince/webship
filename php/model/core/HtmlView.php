<?php

namespace webship\core;

use Exception;
use ship\interoperability\IShippable;
use ship\protocols\ModifyProtocol;
use ship\protocols\TemplateProtocol;
use ship\views\DirectView;
use ship\views\TextView;
use ship\views\View;
use webship\containers\ContainerHtmlView;
use webship\contents\DescribedHtmlView;
use webship\forms\FormHtmlView;
use webship\interoperability\ParameterSerializer;
use webship\menus\MenuHtmlView;
use webship\panels\PanelHtmlView;
use webship\protocols\HtmlEventProtocol;
use webship\WebshipTools;

/**
 * View HtmlView
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlView extends View {

    /**
     * Constants to define whether CSS style were already deliver, or not.
     */
    private const DELIVERED = '__style_delivered';
    private const UNDELIVERED = '__style_undelivered';

    /**
     * A debugging flag.
     */
    private const DEBUG = false;

    /**
     * Styling code.
     * @var string[][]
     */
    protected static array $styles = [];

    /**
     * Local styles.
     * @var string[]
     */
    protected array $localStyles = [];

    /**
     * Styles that may change dynamical by PHP but cannot be solved via variables.
     * Do not use them frequently.
     * @var string[]
     */
    protected array $dynamicStyles = [];

    /**
     * JavaScript codes.
     * @var string[]
     */
    protected array $javaScripts = [];

    /**
     * An array of strings.
     * @var string[]
     */
    protected array $classes;

    /**
     * Some data.
     * @var array|null
     */
    protected ?array $data = null;

    /**
     * Some notices.
     * @var string[]|null
     */
    protected ?array $notices = null;

    /**
     * HtmlView constructor.
     * @param string[]|string $classes (optional) Possible classes
     */
    public function __construct(array|string $classes = []) {
        $classes = is_array($classes) ? $classes : [$classes];
        parent::__construct(__CLASS__);
        $this->classes = $classes;
    }

    /**
     * Generates the opening tag.
     * @return string
     */
    public function generateOpeningTag() : string {
        $id = $this->id();
        return '<div class="' . implode(' ', array_merge($this->classes, [$id])) . '"' .
            ($this->notices ? ' title="' . (array_key_exists(View::getLanguage(), $this->notices) ? $this->notices[View::getLanguage()] : array_pop($this->notices)) . '"' : '') .
            ($id ? ' data-id="' . $id . '"' : '') .
            ($this->data ? ' ' . implode(' ', array_map(function ($key, $val) {
                    return 'data-' . $key . ($val !== null ? '="' . $val . '"' : '');
                }, array_keys($this->data), $this->data)) : '') .
            ($this->localStyles ? ' style="' . str_replace('"', "'", implode(';', $this->localStyles)) . '"' : '') .
            '>';
    }

    /**
     * Generates the closing tag.
     * @return string
     */
    public function generateClosingTag() : string {
        return '</div>';
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function generate(bool $complete = false, bool $templates = false, bool $force = false) : string {
        if (!WebshipTools::isEmpty($this->hints) && !WebshipTools::isEmpty($this->hint(View::getLanguage()))) {
            $this->js('addHintListener("' . $this->idc() . '" , "<ul>' . implode(array_map(function ($str) {
                    return '<li>' . str_replace('"', "'", $str) . '</li>';
                }, $this->hint(View::getLanguage()))) . '</ul>' . '")');
        }
        $code = $this->generateOpeningTag();
        $code .= implode(array_map(function (View $view) {
            return $view->generateSafe();
        }, $this->subviews));
        if ($templates) {
            if (isset($_SESSION) && !$force) {
                if (!array_key_exists('templates', $_SESSION)) $_SESSION['templates'] = array();
                $context = &$_SESSION['templates'];
            } else $context = [];
            $code .= '<script>' . TemplateProtocol::generateAll($context) . '</script>';
        }
        if ($complete) {
            $code .= '<script>' . $this->generateJS() . '</script>';
        }
        $code .= $this->generateClosingTag();
        $code .= ($complete ? '<style type="text/css">' . $this->generateStyle($force) . '</style>' : '');
        return $code;
    }

    /**
     * Add JavaScript to the view.
     * @param string[]|string $js The code.
     */
    public function addJavaScript(array|string $js) : void {
        if (!is_array($js)) $js = [ $js ];
        foreach ($js as $j) {
            if (!in_array($j, $this->javaScripts)) {
                $this->javaScripts[] = $j;
            }
        }
    }

    /**
     * Add CSS code.
     * @return void
     */
    public static function addCSS() : void {}

    /**
     * Add a style to the view.
     * @param string|string[] $style The code.
     */
    public static function addStyle(string|array $style) : void {
        if (!is_array($style)) $style = [$style];
        $class = get_called_class();
        foreach ($style as $s) {
            if (isset($_SESSION)) {
                if (!array_key_exists('styles', $_SESSION)) $_SESSION['styles'] = [];
                if (!array_key_exists($class, $_SESSION['styles'])) {
                    $_SESSION['styles'][$class] = self::UNDELIVERED;
                }
            }
            if (!array_key_exists($class, self::$styles)) self::$styles[$class] = [];
            if (!in_array($s, self::$styles[$class])) {
                self::$styles[$class][] = $s;
            }
        }
    }

    /**
     * Adds a dynamic style.
     * @param string|string[] $style The dynamic style(s) to add.
     * @return void
     */
    public function addDynamicStyle(string|array $style) : void {
        if (!is_array($style)) $style = [$style];
        $this->dynamicStyles = array_merge($this->dynamicStyles, $style);
    }

    /**
     * Add local styles.
     * @param string[]|string $style The style(s).
     * @return void
     */
    public function addLocalStyle(array|string $style) : void {
        if (!is_array($style)) $style = [$style];
        $this->localStyles = array_merge($this->localStyles, $style);
    }

    /**
     * Generates a complete style sheet.
     * @param bool $force Whether the generation of a new style shall be forced, or not.
     * @return string
     * @throws Exception
     */
    public function generateStyle(bool $force = false) : string {
        $styles = [];
        return implode(PHP_EOL, array_unique($this->collectStyles($styles, $force)));
    }

    /**
     * Collect the styles.
     * @param string[] $styles The styles.
     * @param bool $force Whether the generation of a new style shall be forced, or not.
     * @param string[]|null $checkContainer (Internal) container for CSS.
     * @return string[]
     * @throws Exception
     */
    private function collectStyles(array &$styles, bool $force = false, ?array &$checkContainer = null) : array {
        if ($this->isSafe()) {
            $class = get_called_class();
            if (is_null($checkContainer)) {
                if (isset($_SESSION)) {
                    if (!array_key_exists('styles', $_SESSION)) $_SESSION['styles'] = [];
                    $checkContainer = &$_SESSION['styles'];
                } else $checkContainer = [];
            }
            if (!array_key_exists($class, self::$styles)) self::$styles[$class] = [];
            foreach (self::$styles as $class => $cStyles) {
                if (!array_key_exists($class, $checkContainer) ||
                    $checkContainer[$class] === self::UNDELIVERED ||
                    self::DEBUG || $force) {
                    foreach ($cStyles as $s) {
                        if (!in_array($s, $styles)) $styles[] = $s;
                    }
                    $checkContainer[$class] = self::DELIVERED;
                }
            }
            $styles = array_merge($styles, $this->dynamicStyles);
            foreach ($this->subviews as $subView) {
                if ($subView instanceof HtmlView) $subView->collectStyles($styles, false, $checkContainer);
            }
        }
        return $styles;
    }

    /**
     * Generates a complete java script.
     * @return string
     * @throws Exception
     */
    public function generateJS() : string {
        return implode(PHP_EOL, array_unique($this->collectJS()));
    }

    /**
     * Collect the java scripts.
     * @return string[]
     * @throws Exception
     */
    private function collectJS() : array {
        if ($this->isSafe()) {
            $this->javaScripts = array_merge($this->javaScripts, array_map(function (HtmlEventProtocol $p) {
                return $p->generate();
            }, $this->protocols));
            return array_merge($this->javaScripts, array_reduce($this->subviews, function (array $jss, View $subView) {
                if ($subView instanceof HtmlView) {
                    return array_merge($jss, $subView->collectJS());
                } else {
                    return $jss;
                }
            }, []));
        } else return [];
    }

    /**
     * @inheritDoc
     */
    public function ref(string|IShippable $id, bool|string $scope = false, DirectView|bool $view = false) : static {
        if ($scope !== false && $scope !== 'value') $this->data('intern-scope', $scope);
        return parent::ref($id, $scope, $view);
    }


    /**
     * @inheritDoc
     */
    public function protocol(int $event, array $params = []) : ?ModifyProtocol {
        $pro = new HtmlEventProtocol($event, $this, $params);
        if (!$this->uniqueId) $this->uniqueId = $this->generateId();
        $this->protocols[] = $pro;
        return $pro->modify();
    }

    /**
     * @inheritDoc
     */
    public function firstProtocol(int $event, array $params = []) : ?ModifyProtocol {
        $pro = new HtmlEventProtocol($event, $this, $params);
        if (!$this->uniqueId) $this->uniqueId = $this->generateId();
        array_unshift($this->protocols, $pro);
        return $pro->modify();
    }

    ////////////////////////
    // Shortcuts
    ////////////////////////

    /**
     * @param string|string[] $js The code.
     * @return HtmlView $this
     * @see HtmlView->addJavaScript.
     */
    public function js(string|array $js) : static {
        $this->addJavaScript($js);
        return $this;
    }

    /**
     * Add style to Webship.
     * @param string|string[] $style The code.
     * @return static
     * @see HtmlView->addStyle
     */
    public function style(string|array $style) : static {
        self::addStyle($style);
        return $this;
    }

    /**
     * Add local style to the view.
     * @param string|string[] $style The style(s).
     * @return static
     * @see HtmlView->addLocalStyle
     */
    public function localStyle(string|array $style) : static {
        $this->addLocalStyle($style);
        return $this;
    }

    /**
     * Generate a panel.
     * @param string|array|string $classes One or more classes.
     * @return PanelHtmlView
     */
    public function panel(array|string $classes = []) : PanelHtmlView {
        $panelView = PanelHtmlView::createIt($classes);
        $this->add($panelView);
        return $panelView;
    }

    /**
     * Generate a container.
     * @param string[]|string $classes One or more classes.
     * @return ContainerHtmlView
     */
    public function container(array|string $classes = []) : ContainerHtmlView {
        $containerView = new ContainerHtmlView($classes);
        $this->add($containerView);
        return $containerView;
    }

    /**
     * Generate a menu.
     * @param string[]|string $classes Some classes.
     * @return MenuHtmlView
     */
    public function menu(array|string $classes = []) : MenuHtmlView {
        $menuView = new MenuHtmlView($classes);
        $this->add($menuView);
        return $menuView;
    }

    /**
     * Add a described sub item.
     * @param string[]|string $classes Some classes.
     * @param string|null $labelFor A label.
     * @return DescribedHtmlView
     */
    public function described(array|string $classes = [], string|null $labelFor = null) : DescribedHtmlView {
        $described = new DescribedHtmlView($classes, $labelFor);
        $this->add($described);
        return $described;
    }

    /**
     * Add a form.
     * @param string $action The action to perform.
     * @param string $method The method.
     * @param string[]|string $classes Classes.
     * @return FormHtmlView
     */
    public function form(string $action = '', string $method = '', array|string $classes = []) : FormHtmlView {
        $formView = FormHtmlView::create($classes, $action, $method);
        $this->add($formView);
        return $formView;
    }

    /**
     * Add texts.
     * @param string[]|string|null $texts A language-indexed optional set of texts.
     * @return HtmlView|TextView|static
     */
    public function texts(array|string|null $texts = null) : HtmlView|TextView|static {
        $textView = new TextView();
        $this->add($textView);
        if ($texts) {
            if (is_string($texts)) $texts = [View::getLanguage() => $texts];
            foreach ($texts as $lang => $text) {
                $textView->text($lang, $text);
            }
            return $this;
        }
        return $textView;
    }

    /**
     * Get the parent.
     * @return HtmlView|null
     */
    public function parent() : ?HtmlView {
        $p = $this->getParent();
        if ($p instanceof HtmlView) {
            return $p;
        } else {
            return null;
        }
    }

    /**
     * Return the id class for html.
     * @return string
     */
    public function idc() : string {
        if (!$this->uniqueId) $this->uniqueId = $this->generateId();
        return '.' . $this->id();
    }

    /**
     * Adds a class to this view.
     * @param string|string[] $class The class(es).
     * @return void
     */
    public function addClass(string|array $class) : void {
        if (!is_array($class)) $class = [ $class ];
        $this->classes = array_unique(array_merge($this->classes, $class));
    }

    /**
     * Find a set of views by a selector.
     * @param string $selector The selector.
     * @return HtmlView[]
     */
    public function find(string $selector) : array {
        $classes = (!WebshipTools::isEmpty($this->classes) ? '.' : '') . implode('.', $this->classes);
        $res = strpos($selector, $classes) >= 0 ? [$this] : [];
        return array_reduce($this->subviews, function ($res, HtmlView $view) use ($selector) {
            return array_merge($res, $view->find($selector));
        }, $res);
    }

    /**
     * @inheritDoc
     */
    public function bind($object, array|int|null $event = null, ModifyProtocol|string|null $protocol = null,
                         ?string $scope = null, ?array $ignoreData = null, ?DirectView $view = null) : static {
        if ($object && !is_null($event) && $protocol) {
            $objId = ParameterSerializer::handleObject($object);
            if (!is_array($event)) $event = [$event];
            $this->js('eventBus.bind($("' . $this->idc() . '"), "' . $objId["IClass"] . $objId["value"] . '", ' .
                '["' . implode('", "', $event) . '"], function(eData) { ' .
                ($protocol instanceof ModifyProtocol ? $protocol->generate() : $protocol) . '}, ' .
                (is_array($ignoreData) && !WebshipTools::isEmpty($ignoreData) ? ('["' . implode('","', $ignoreData) . '"]') : 'null') .
                ($scope ? ', "' . $scope . '"' : '') . ');');
            if (is_string($protocol)) $protocol = null;
        }
        return parent::bind($object, $event, $protocol, $scope, $ignoreData, $view);
    }

    /**
     * Add a data value to the view.
     * @param string $key The key of the data.
     * @param string|bool|int|null $value The value.
     * @return void
     */
    public function data(string $key, string|bool|int|null $value = null) : void {
        if (!$this->data) $this->data = [];
        $this->data[$key] = $value;
    }

    /**
     * Add an notice.
     * @param int $language The language.
     * @param string $text The notice.
     * @return $this
     */
    public function notice(int $language, string $text) : static {
        if (!$this->notices) $this->notices = [$language => $text];
        else $this->notices[$language] = $text;
        return $this;
    }

    /**
     * Shortcut for notice.
     * @param string[]|string $texts The language-indexed texts.
     * @return $this
     */
    public function notices(array|string $texts) : static {
        if (!is_array($texts)) $texts = [self::getLanguage() => $texts];
        foreach ($texts as $lang => $text) {
            $this->notice($lang, $text);
        }
        return $this;
    }

    /**
     * Add multiple arrays to a view.
     * @param HtmlView[] $views An array of views to add.
     * @return $this
     */
    public function addViews(array $views) : static {
        $this->each($views, function (HtmlView $th, HtmlView $that) {
            $th->add($that);
        });
        return $this;
    }
}
?>