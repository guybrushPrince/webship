<?php

include_once __DIR__ . '/../../../ship/php/Context.php';

/**
 * Class HtmlContext
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlContext extends Context {

    /**
     * @inheritDoc
     */
    public function generate() {
        return 'context["' . $this->field . '"]';
    }

    /**
     * Get or create a context.
     * @param string $field The field.
     * @return Context
     */
    public static function getOrCreate($field) {
        return Context::get($field, __CLASS__);
    }

}
?>