<?php

namespace webship\interoperability;

use ship\interoperability\Context;

/**
 * Class HtmlContext
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlContext extends Context {

    /**
     * @inheritDoc
     */
    public function generate() : string {
        return 'context["' . $this->field . '"]';
    }

    /**
     * Get or create a context.
     * @param string $field The field.
     * @return Context
     */
    public static function getOrCreate(string $field) : Context {
        return Context::get($field, __CLASS__);
    }

}
?>