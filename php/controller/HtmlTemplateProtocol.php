<?php

use ship\protocols\ModifyProtocol;
use ship\protocols\Protocol;
use ship\protocols\TemplateProtocol;
use ship\views\DirectView;

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
    public function __construct(string $name, string $environment = 'global') {
        parent::__construct($name, "HtmlModifyProtocol", $environment);
    }

    /**
     * Generate a new template.
     * @param string $name The name.
     * @param string $environment The environment.
     * @return ModifyProtocol
     */
    public static function _new(string $name, string $environment = 'global') : ModifyProtocol {
        $template = new HtmlTemplateProtocol($name, $environment);
        return $template->modify();
    }

    /**
     * @inheritDoc
     */
    protected function createListener() : string {
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
    public function createThrower(array $context = array(),
                                  DirectView|Protocol|null $entryView = null) : string {
        $view = ($entryView instanceof DirectView || $entryView === null) ? $entryView : $entryView->innerView();
        $data = ParameterSerializer::serialize($this, $this->collectParameters(), $context, $view);
        return $this->getCallName() . '(Object.assign({' . implode(',', $data) . '}, (typeof eData !== "undefined" ? eData : {})));';
    }

    private function generateCode(array $protocols) : string {
        return implode(array_map(function (Protocol $p) {
            return $p->generate();
        }, $protocols));
    }
}
?>