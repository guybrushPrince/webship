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
        $code = 'let ' . $this->getCallName() . ' = function(con) {';
        $code .= $this->generateCode($this->children);
        $code .= '};';
        return $code;
    }

    /**
     * @inheritDoc
     */
    public function createThrower(array $context = array(), DirectView $entryView = null) {
        DebugLogger::instance()->debug('Try to create thrower');
        $data = ParameterSerializer::serialize($this, $this->collectParameters(), $context, $entryView);

        $code = $this->getCallName() . '({' . implode(',', $data) . '});';
        DebugLogger::instance()->debug('Thrower', $code);
        return $code;
    }

    private function generateCode(array $protocols) {
        return implode(array_map(function (Protocol $p) {
            return $p->generate();
        }, $protocols));
    }
}
?>