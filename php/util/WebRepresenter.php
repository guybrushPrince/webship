<?php

namespace webship;

use Closure;
use Exception;
use ship\interoperability\IShippable;
use ship\interoperability\ShipperRegister;
use ship\interoperability\SLanguage as Language;
use webship\core\HtmlView;

/**
 * Class Representer.
 *
 * <p>The *Representer* is a central place to register visualizations of objects.</p>
 *
 * @title WebResenter
 * @category util
 * 
 * @package progressionui
 * @subpackage php/tools
 *
 * @version 1.0.0
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class WebRepresenter {

    /**
     * A register for different textual representations.
     * @var Closure[]
     */
    private static array $textRepresent = [];

    /**
     * A register for different visual representations.
     * @var Closure[]
     */
    private static array $visualRepresent = [];

    /**
     * A default representer.
     * @var Closure|null
     */
    private static Closure|null $defaultRepresenter = null;

    /**
     * Register a representer.
     * @param string|string[] $class The class(es) which are represented.
     * @param Closure $representer The representer function which requires (object,language,classes) : string.
     * @return void
     */
    public static function register(string|array $class, Closure $representer) : void {
        if (!is_array($class)) $class = [ $class ];
        foreach ($class as $c) {
            self::$textRepresent[$c] = $representer;
        }
    }

    /**
     * Register a representer.
     * @param string|string[] $class The class(es) which are represented.
     * @param Closure $representer The representer function which requires (object,language,withFunctionality,classes) : HtmlView.
     * @return void
     */
    public static function registerVisual(string|array $class, Closure $representer) : void {
        if (!is_array($class)) $class = [ $class ];
        foreach ($class as $c) {
            self::$visualRepresent[$c] = $representer;
        }
    }

    /**
     * Register a default visualizer.
     * @param Closure $default The function calling the visualizer.
     * @return void
     */
    public static function registerDefault(Closure $default) : void {
        self::$defaultRepresenter = $default;
    }

    /**
     * Get the textual representation of an object.
     * @param IShippable $object The object to represent.
     * @param int $language The language.
     * @param bool $withDesign Whether the text shall be designed slightly.
     * @return string
     */
    public static function getTextualRepresentation(IShippable $object,
                                                    int $language = Language::GERMAN,
                                                    bool $withDesign = false) : string {
        $class = ShipperRegister::getClass($object);
        if (self::hasTextualRepresentation($object)) {
            return self::$textRepresent[$class]($object, $language, $withDesign);
        }
        return $class . ShipperRegister::getKey($object);
    }

    /**
     * Get the visual representation of an object as HtmlView.
     * @param IShippable $object The object to represent.
     * @param int $language The language.
     * @param bool $withFunctionality Whether the representation shall include some functionality, or not.
     * @param IShippable[]|IShippable|null $related Related objects that are required for functionality.
     * @param string[]|string $classes Some classes.
     * @return HtmlView
     * @throws Exception
     */
    public static function getVisualRepresentation(IShippable $object,
                                                   int $language = Language::GERMAN, bool $withFunctionality = true,
                                                   array|IShippable|null $related = null,
                                                   string|array $classes = []) : HtmlView {
        $class = ShipperRegister::getClass($object);
        if (self::hasVisualRepresentation($object)) {
            return self::$visualRepresent[$class]($object, $language, $withFunctionality, $related,
                $classes);
        }

        if (!is_null(self::$defaultRepresenter)) {
            $def = self::$defaultRepresenter;
            return $def($object, $class);
        } else throw new Exception();
    }

    /**
     * Checks if the given class (or object) has a textual representation.
     * @param string|IShippable $clazz The class (or object).
     * @return bool
     */
    public static function hasTextualRepresentation(string|IShippable $clazz) : bool {
        if ($clazz instanceof IShippable) $clazz = ShipperRegister::getClass($clazz);
        return array_key_exists($clazz, self::$textRepresent);
    }

    /**
     * Checks if the given class (or object) has a visual representation.
     * @param string|IShippable $clazz The class (or object).
     * @return bool
     */
    public static function hasVisualRepresentation(string|IShippable $clazz) : bool {
        if ($clazz instanceof IShippable) $clazz = ShipperRegister::getClass($clazz);
        return array_key_exists($clazz, self::$visualRepresent);
    }
}
?>