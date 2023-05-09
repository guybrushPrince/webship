<?php

/**
 * Class JSObject.
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class JSObject {

    /**
     * The name of the object.
     * @var string
     */
    private string $name;

    /**
     * The name of the property containing the id.
     * @var string
     */
    private string $idProp;

    /**
     * The name of the property containing the class name.
     * @var string
     */
    private string $classProp;

    /**
     * Other non-standard properties.
     * @var array
     */
    private array $properties = [];

    /**
     * The parent object.
     * @var JSObject|null
     */
    private ?JSObject $parent = null;

    /**
     * Constructor.
     * @param string $name The name of the object.
     * @param string $idProp The name of the property containing the id.
     * @param string $classProp The name of the property containing the class name.
     * @param array $properties A named array with additional properties.
     */
    public function __construct(string $name, string $idProp = 'id', string $classProp = 'class',
                                array $properties = []) {
        $this->name = $name;
        $this->idProp = $idProp;
        $this->classProp = $classProp;
        $this->properties = array_map(function (string|JSObject $entry) {
            if ($entry instanceof JSObject) {
                $entry->parent = $this;
            }
            return $entry;
        }, $properties);
    }

    /**
     * @return string
     */
    public function getName() : string {
        return ($this->parent ? $this->parent->getName() . '.' : '') . $this->name;
    }

    /**
     * @return string
     */
    public function getIdProp() : string {
        return $this->idProp;
    }

    /**
     * @return string
     */
    public function getClassProp() : string {
        return $this->classProp;
    }

    /**
     * Get the property of the object.
     * @param string $name The name of the property.
     * @return string|JSObject|null
     */
    public function getProperty(string $name) : string|JSObject|null {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        } else return null;
    }

}
?>