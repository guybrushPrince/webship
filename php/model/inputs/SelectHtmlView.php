<?php

namespace webship\inputs;

use ship\interoperability\IShippable;
use ship\interoperability\SLanguage as Language;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View SelectHtmlView.
 *
 * <p>A select (drop-down) element.</p>
 *
 * <code>
 * $select = SelectHtmlView::create('select');
 * $select->each(range(1,10), function (SelectHtmlView $s, int $o) {
 *     $s->option($o)->texts($o . '. entry');
 * });
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/select.png" />
 *
 * @title Select
 * @category inputs
 * 
 * @package webship
 * @subpackage php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.3.0
 *
 * @view
 * @examples
 *     $select = SelectHtmlView::create('select');
 *     $select->each(range(1,10), function (SelectHtmlView $s, int $o) {
 *         $s->option($o)->texts($o . '. entry');
 *     });
 */
class SelectHtmlView extends HtmlView {

    /**
     * An id.
     * @var string|null
     */
    private ?string $id;

    /**
     * The name
     * @var string
     */
    private string $name;

    /**
     * Set the button disabled or not.
     * @var bool
     */
    private bool $disabled = false;

    /**
     * Whether the select is hidden or not.
     * @var bool
     */
    private bool $hidden;

    /**
     * The opening tag.
     * @var string
     */
    private string $opening = 'select';

    /**
     * Whether the singleton option shall be ignored or not.
     * @var bool
     */
    private bool $ignoreSingleton = false;

    /**
     * Constructor.
     * @param string $id The id.
     * @param string $name The name.
     * @param string[]|string $classes Some classes.
     * @param bool $hidden If this field is invisible.
     * @param IShippable|array|string|null $default The default value (array is just for super classes).
     */
    protected function __construct(string $id, string $name, array|string $classes = [], bool $hidden = false,
                                IShippable|array|string|null $default = '-1') {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'select' ], $classes));
        $this->id = $id;
        $this->name = $name;
        $this->hidden = $hidden;

        if (is_null($default)) $default = 'null';

        $this->option($default)->texts([
            Language::ENGLISH => 'Please select',
            Language::GERMAN  => 'Bitte auswählen'
        ]);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('select', [], 'box-sizing: border-box', 'padding: 5px',
            'height: 50px', 'width: 100%', 'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));
    }

    /**
     * Create a select.
     * @param string $id The id.
     * @param string[]|string $classes Some classes.
     * @param bool $hidden If this field is invisible.
     * @param IShippable|array|string|null $default The default value (array is just for super classes).
     * @param string|null $name The name.
     */
    public static function create(string $id, array|string $classes = [], bool $hidden = false,
                                  IShippable|array|string|null $default = '-1',
                                  string|null $name = null) : SelectHtmlView {
        return new SelectHtmlView($id, is_null($name) ? $id : $name, $classes, $hidden, $default);
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        if (count($this->subviews) === 1) {
            $this->subviews = array();
            $this->texts()->
                text(Language::ENGLISH, 'No options available')->
                text(Language::GERMAN,  'Keine Auswahl verfügbar');
            $this->opening = 'div';
            return '<div class="' . implode(' ', array_merge($this->classes, [ $this->id() ])) . '">';
        } else  {
            if (count($this->subviews) === 2 && !$this->ignoreSingleton) {
                $this->subviews = [ $this->subviews[1] ];
                $this->classes[] = "singleton";
            }
            return '<select class="' . implode(' ', array_merge($this->classes, [ $this->id() ])) . '" ' .
                'id="' . $this->id . '" name="' . $this->name . '"' .
                ($this->disabled ? ' disabled' : '') . ($this->hidden ? ' hidden' : '') .
                ($this->data ? ' ' . implode(' ', array_map(function($key, $val) {
                        return 'data-' . $key . ($val !== null ? '="' . $val . '"' : '');
                    }, array_keys($this->data), $this->data)) : '') .
                '>';
        }
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</' . $this->opening . '>';
    }

    // Shortcuts

    /**
     * Add an option.
     * @param array|IShippable|string|float|int|null $value The value (array only for specializations).
     * @param string[]|string $classes Some classes.
     * @param bool $selected Whether it is selected.
     * @return OptionHtmlView
     */
    public function option(array|IShippable|string|float|int|null $value, array|string $classes = [],
                           bool $selected = false) : OptionHtmlView {
        $option = OptionHtmlView::create($value, $selected, $classes);
        $this->add($option);
        return $option;
    }

    /**
     * Set disabled.
     * @param bool $disabled The new value.
     * @return $this
     */
    public function disabled(bool $disabled) : SelectHtmlView {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * Get the id.
     * @return string|null
     */
    public function getId() : ?string {
        return $this->id;
    }

    /**
     * Whether it is singleton or not.
     * @param bool $singleton Singleton?
     * @return $this
     */
    public function singleton(bool $singleton) : static {
        $this->ignoreSingleton = !$singleton;
        return $this;
    }
}
?>