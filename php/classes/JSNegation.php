<?php
/**
 * Class JSNegation.
 *
 * @package webship
 * @subpackage php/classes
 *
 * @version 1.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class JSNegation {

    /**
     * The property to negate.
     * @var JSProperty
     */
    private JSProperty $property;

    /**
     * The constructor.
     * @param JSProperty $property The property to negate.
     */
    public function __construct(JSProperty $property) {
        $this->property = $property;
    }

    /**
     * Get the expression (negated property).
     * @return string
     */
    public function getExpression() : string {
        return '!' . $this->property->getName();
    }
}
?>