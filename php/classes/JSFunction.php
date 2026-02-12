<?php

use ship\template\TemplateObject;

class JSFunction {
    private string|TemplateObject $code;

    public function __construct(string|TemplateObject $code) {
        $this->code = $code;
    }

    public function generate() : string {
        return '(function(result) { ' . $this->code . ' })';
    }

    public function getCode() : string|TemplateObject {
        return $this->code;
    }
}
?>