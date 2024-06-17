<?php

include_once dirname(__FILE__) . '/../../../ship/php/TemplateProtocol.php';

/**
 * Class HtmlTemplateProtocol
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlTemplateProtocol extends TemplateProtocol {
    /**
     * HtmlTemplateProtocol constructor.
     * @param string $name The name of the template/event.
     * @param string $environment The environment.
     */
    public function __construct($name, $environment = 'global') {
        parent::__construct($name, "HtmlModifyProtocol", $environment);
    }

    /**
     * Generate a new template.
     * @param string $name The name.
     * @param string $environment The environment.
     * @return ModifyProtocol
     */
    public static function _new($name, $environment = 'global') {
        $template = new HtmlTemplateProtocol($name, $environment);
        return $template->modify();
    }

    /**
     * @inheritDoc
     */
    protected function createListener() {
        $name = $this->getCallName();
        $code = 'if (typeof ' . $name . ' === "undefined") {';
        $code .= 'var ' . $name . ' = function(con) {';
        $code .= 'let _i_n_ = "' . $name . '";';
        $code .= $this->generateCode($this->children);
        $code .= '};';
        $code .= '}';
        return $code;
    }

    /**
     * @inheritDoc
     */
    public function createThrower(array $context = array(), $entryView = null) : string {
        $view = ($entryView instanceof DirectView || $entryView === null) ? $entryView : $entryView->innerView();
        $data = ParameterSerializer::serialize($this, $this->collectParameters(), $context, $view);
        return $this->getCallName() . '(Object.assign({' . implode(',', $data) . '}, (typeof eData !== "undefined" ? eData : {})));';
    }

    private function generateCode(array $protocols) {
        return implode(array_map(function (Protocol $p) {
            return $p->generate();
        }, $protocols));
    }
}
?>