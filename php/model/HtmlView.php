<?php

include_once dirname(__FILE__) . '/../../../ship/php/model/View.php';

/**
 * Class HtmlView
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
abstract class HtmlView extends View {

    /**
     * Styling code.
     * @var array: string
     */
    protected $styles = array();

    /**
     * JavaScript codes.
     * @var array: string
     */
    protected $javaScripts = array();

    /**
     * An array of strings.
     * @var array: string
     */
    protected $classes;

    /**
     * HtmlView constructor.
     * @param array $classes (optional) Possible classes
     */
    public function __construct(array $classes = array()) {
        parent::__construct();
        $this->classes = array_merge(array('sunstone'), $classes);
    }

    /**
     * Generates the opening tag.
     * @return string
     */
    public function generateOpeningTag() {
        return '<div class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '">';
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
    public function generate($complete = false) {
        $code = $this->generateOpeningTag();
        $code .= implode(array_map(function(View $view) { return $view->generateSafe(); }, $this->subviews));
        if ($complete) {
            $code .= '<style type="text/css">' . $this->generateStyle() . '</style>' .
                '<script>' . $this->generateJS() . '</script>';
        }
        $code .= $this->generateClosingTag();
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
            if (!in_array($s, $this->styles)) {
                $this->styles[] = $s;
            }
        }
    }

    /**
     * Generates a complete style sheet.
     * @return string
     */
    public function generateStyle() {
        return implode("\n", array_unique($this->collectStyles()));
    }

    /**
     * Collect the styles.
     * @return array
     */
    private function collectStyles() {
        if ($this->isSafe()) {
            return array_merge(array_reduce($this->subviews, function (array $styles, View $subView) {
                if ($subView instanceof HtmlView) {
                    return array_merge($styles, $subView->collectStyles());
                } else {
                    return $styles;
                }
            }, array()), $this->styles);
        } else return array();
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
            return array_merge(array_reduce($this->subviews, function (array $jss, View $subView) {
                if ($subView instanceof HtmlView) {
                    return array_merge($jss, $subView->collectJS());
                } else {
                    return $jss;
                }
            }, array()), $this->javaScripts);
        } else return array();
    }

    /**
     * @inheritDoc
     */
    public function protocol($event, $delay = false) {
        $prot = new HtmlEventProtocol($event, $this, $delay);
        if (!$this->uniqueId) $this->uniqueId = $this->generateId();
        $this->protocols[] = $prot;
        $prot = $prot->modify();
        return $prot;
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
     * @return PanelHtmlView
     */
    public function panel(array $classes = array()) {
        $panelView = new PanelHtmlView($classes);
        $this->add($panelView);
        return $panelView;
    }

    /**
     * Generate a container.
     * @return ContainerHtmlView
     */
    public function container(array $classes = array()) {
        $containerView = new ContainerHtmlView($classes);
        $this->add($containerView);
        return $containerView;
    }

    /**
     * Generate a menu.
     * @return MenuHtmlView
     */
    public function menu(array $classes = array()) {
        $menuView = new MenuHtmlView($classes);
        $this->add($menuView);
        return $menuView;
    }

    /**
     * Add a described sub item.
     * @param array $classes Classes.
     * @param string|boolean $labelFor A label.
     * @return DescribedHtmlView
     */
    public function described(array $classes = array(), $labelFor = false) {
        $described = new DescribedHtmlView($classes, $labelFor);
        $this->add($described);
        return $described;
    }

    /**
     * Add a form.
     * @param string $action The action to perform.
     * @param string $method The method.
     * @param array $classes Classes.
     * @return FormHtmlView
     */
    public function form($action, $method, array $classes = array()) {
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
    public function parent() {
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

}
?>