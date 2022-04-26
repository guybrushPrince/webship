<?php

include_once dirname(__FILE__) . '/../../../ship/php/model/View.php';

/**
 * Class HtmlView
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlView extends View {

    /**
     * Styling code.
     * @var array: string
     */
    protected array $styles = array();

    /**
     * JavaScript codes.
     * @var array: string
     */
    protected array $javaScripts = array();

    /**
     * An array of strings.
     * @var array: string
     */
    protected $classes;

    /**
     * HtmlView constructor.
     * @param string[]|string $classes (optional) Possible classes
     */
    public function __construct($classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct();
        $this->classes = $classes;
    }

    /**
     * Generates the opening tag.
     * @return string
     */
    public function generateOpeningTag() {
        $id = $this->id();
        return '<div class="' . implode(' ', array_merge($this->classes, array($id))) . '"' .
            ($id ? ' data-id="' . $id . '"' : '') . '>';
    }

    /**
     * Generates the closing tag.
     * @return string
     */
    public function generateClosingTag() {
        return '</div>';
    }

    /**
     * @inheritDoc
     */
    public function generate($complete = false, $templates = false) : string {
        if (!Tools::isEmpty($this->hints) && !Tools::isEmpty($this->hint(View::getLanguage()))) {
            $this->js(
                '(() => {' .
                    'let hTO;' .
                    'let h = false;' .
                    '$("' . $this->idc() . '").hover(function(event) {' .
                        'sp(event); if (event.target !== this) return;' .
                        'hTO = setTimeout(() => {' .
                            'if (!$("' . $this->idc() . '").exists()) return;' .
                            'h = showHint("' . $this->idc() . '", "' . '<ul>' . implode(array_map(function ($str) {
                                return '<li>' . str_replace('"', "'", $str) . '</li>';
                            }, $this->hint(View::getLanguage()))) . '</ul>' . '");
                        }, 2000);
                    }, () => {
                        clearTimeout(hTO);
                        if (h !== false) hideHint(h);
                    });' .
                '})();');
        }
        $code = $this->generateOpeningTag();
        $code .= implode(array_map(function(View $view) { return $view->generateSafe(); }, $this->subviews));
        if ($templates) {
            if (isset($_SESSION)) {
                if (!array_key_exists('templates', $_SESSION)) $_SESSION['templates'] = array();
                $context = &$_SESSION['templates'];
            } else $context = array();
            $code .= '<script>' . TemplateProtocol::generateAll($context) . '</script>';
        }
        if ($complete) {
            $code .= /*'<style type="text/css">' . $this->generateStyle() . '</style>' .*/
                '<script>' . $this->generateJS() . '</script>';
        }
        $code .= $this->generateClosingTag();
        $code .= ($complete ? '<style type="text/css">' . $this->generateStyle() . '</style>' : '');
        return $code;
    }

    /**
     * Add java script to the view.
     * @param string|array $js The code.
     */
    public function addJavaScript($js) {
        if (!is_array($js)) $js = array( $js );
        foreach ($js as $j) {
            if (!in_array($j, $this->javaScripts)) {
                $this->javaScripts[] = $j;
            }
        }
    }

    /**
     * Add a style to the view.
     * @param string|array $style The code.
     */
    public function addStyle($style) {
        if (!is_array($style)) $style = array( $style );
        foreach ($style as $s) {
            if (isset($_SESSION)) {
                if (!array_key_exists('styles', $_SESSION)) $_SESSION['styles'] = array();
                if (!in_array($s, $_SESSION['styles'])) {
                    $_SESSION['styles'][] = $s;
                }
                if (!in_array($s, $this->styles)) {
                    $this->styles[] = $s;
                }
            } else {
                if (!in_array($s, $this->styles)) {
                    $this->styles[] = $s;
                }
            }
        }
    }

    /**
     * Generates a complete style sheet.
     * @return string
     */
    public function generateStyle() {
        $styles = array();
        return implode("\n", array_unique($this->collectStyles($styles)));
    }

    /**
     * Collect the styles.
     * @param array $styles The styles.
     * @return array
     */
    private function collectStyles(&$styles) {
        if ($this->isSafe()) {
            foreach ($this->styles as $s) {
                if (!in_array($s, $styles)) $styles[] = $s;
            }
            foreach ($this->subviews as $subView) {
                if ($subView instanceof HtmlView) $subView->collectStyles($styles);
            }
        }
        return $styles;
    }

    /**
     * Generates a complete java script.
     * @return string
     */
    public function generateJS() {
        return implode("\n", array_unique($this->collectJS()));
    }

    /**
     * Collect the java scripts.
     * @return array
     */
    private function collectJS() {
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
            }, array()));
        } else return array();
    }

    /**
     * @inheritDoc
     */
    public function protocol(int $event, array $params = []) {
        $pro = new HtmlEventProtocol($event, $this, $params);
        if (!$this->uniqueId) $this->uniqueId = $this->generateId();
        $this->protocols[] = $pro;
        $pro = $pro->modify();
        return $pro;
    }

    // Short cuts

    /**
     * @see HtmlView->addJavaScript.
     * @param string|array $js The code.
     * @return HtmlView $this
     */
    public function js($js) {
        $this->addJavaScript($js);
        return $this;
    }

    /**
     * @see HtmlView->addStyle
     * @param string|array $style The code.
     * @return HtmlView $this
     */
    public function style($style) {
        $this->addStyle($style);
        return $this;
    }

    /**
     * Generate a panel.
     * @param string|array|string $classes One or more classes.
     * @return PanelHtmlView
     */
    public function panel($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $panelView = new PanelHtmlView($classes);
        $this->add($panelView);
        return $panelView;
    }

    /**
     * Generate a container.
     * @param string|array|string $classes One or more classes.
     * @return ContainerHtmlView
     */
    public function container($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $containerView = new ContainerHtmlView($classes);
        $this->add($containerView);
        return $containerView;
    }

    /**
     * Generate a menu.
     * @param string|array|string $classes One or more classes.
     * @return MenuHtmlView
     */
    public function menu($classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $menuView = new MenuHtmlView($classes);
        $this->add($menuView);
        return $menuView;
    }

    /**
     * Add a described sub item.
     * @param array|string $classes Classes.
     * @param string|boolean $labelFor A label.
     * @return DescribedHtmlView
     */
    public function described($classes = array(), $labelFor = false) {
        $described = new DescribedHtmlView($classes, $labelFor);
        $this->add($described);
        return $described;
    }

    /**
     * Add a form.
     * @param string $action The action to perform.
     * @param string $method The method.
     * @param array|string $classes Classes.
     * @return FormHtmlView
     */
    public function form($action, $method, $classes = array()) {
        $classes = is_array($classes) ? $classes : array($classes);
        $formView = new FormHtmlView($action, $method, $classes);
        $this->add($formView);
        return $formView;
    }

    /**
     * Add texts.
     * @return TextView
     */
    public function texts() {
        $textView = new TextView();
        $this->add($textView);
        return $textView;
    }

    /**
     * @return HtmlView
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
    public function idc() {
        if (!$this->uniqueId) $this->uniqueId = $this->generateId();
        return '.' . $this->id();
    }

    /**
     * Adds a class to this view.
     * @param string $class The class.
     * @return void
     */
    public function addClass(string $class) {
        $this->classes[] = $class;
    }

}
?>