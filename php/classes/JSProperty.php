<?php

namespace webship;

use ship\template\Property;

/**
 * Class JSProperty.
 *
 * @package webship
 * @subpackage php/classes
 *
 * @version 1.2.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class JSProperty extends Property {

    /**
     * The name of the property.
     * @var string
     */
    private string $name;

    /**
     * The parent object.
     * @var JSObject|null
     */
    private ?JSObject $parent = null;

    /**
     * Constructor.
     * @param string $name The name of the property.
     */
    public function __construct(string $name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() : string {
        return ($this->parent ? $this->parent->getName() . '.' : '') . $this->name;
    }

    public function setParent(?JSObject $parent) : void {
        $this->parent = $parent;
    }
}
?>