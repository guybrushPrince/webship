<?php

namespace webship\inputs;

use webship\interoperability\WEvent;
use ship\views\View;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View TextHtmlView.
 *
 * <p>A textarea to allow input in multiple text rows.</p>
 *
 * <code>
 * TextHtmlView::create('text-1');
 * TextHtmlView::create('text-2', false, 5);
 * TextHtmlView::create('text-3', true);
 * </code>
 *
 * <img src="http://localhost/coast/webship/modeldoc/text.png" />
 *
 * @name TextHtmlView
 * @category inputs
 * @package webship
 * @subpackge php/model/inputs
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.3.0
 *
 * @view
 * @examples
 *     TextHtmlView::create('text-1');
 *     TextHtmlView::create('text-2', false, 5);
 *     TextHtmlView::create('text-3', true);
 */
class TextHtmlView extends HtmlView {

    /**
     * An id.
     * @var string|null
     */
    private string|null $id;

    /**
     * The name.
     * @var string
     */
    private string $name;

    /**
     * The number of rows.
     * @var int
     */
    private int $rows;

    /**
     * The maximum number of characters being allowed.
     * @var int|null
     */
    private int|null $max;

    /**
     * Set the button disabled or not.
     * @var bool
     */
    private bool $disabled = false;

    /**
     * InputHtmlView constructor.
     * @param string|null $id The id.
     * @param string $name The name.
     * @param int $rows The rows.
     * @param int|null $max The maximum.
     * @param string[]|string $classes Some classes.
     * @param bool $resizable If the text area shall be resizable.
     */
    protected function __construct(?string $id, string $name, int $rows = 3, int|null $max = null,
                                 array|string $classes = [], bool $resizable = false) {
        $classes = is_array($classes) ? $classes : [ $classes ];
        parent::__construct(array_merge([ 'text-input' ], ($resizable ? [ 'resizable' ] : []) , $classes));
        $this->id = $id;
        $this->name = $name;
        $this->rows = $rows;
        $this->max = $max;

        $this->localStyle('--ws-ta-rows: ' . $rows);

        if ($resizable) $this->protocol(WEvent::INPUT)->reheight($this);

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('textarea', '', 'box-sizing: border-box', 'padding: 5px',
            'height: calc(var(--ws-ta-rows) * 50px)', 'width: 100%',
            'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));

        self::addStyle(WebshipTools::css('textarea', '.resizable', 'height: 31px',
            'overflow: hidden'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        $hints = $this->hints;
        $this->hints = [];
        return '<textarea class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->id ? 'id="' . $this->id . '"' : '') . ' name="' . $this->name . '" rows="' . $this->rows . '"' .
            ($this->max ? ' maxlength="' . $this->max . '"' : '') .
            (array_key_exists(View::$language, $hints) ?
                ' placeholder="' . implode(' ', $hints[View::$language]) . '"' :
                '') .
            ($this->data ? ' ' . implode(' ', array_map(function($key, $val) {
                    return 'data-' . $key . ($val !== null ? '="' . $val . '"' : '');
                }, array_keys($this->data), $this->data)) : '') .
            ($this->disabled ? ' disabled' : '') .
            '>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</textarea>';
    }

    /**
     * Set/get disabled.
     * @param ?bool $disabled The new value.
     * @return $this|bool
     */
    public function disabled(?bool $disabled = null): TextHtmlView|bool {
        if ($disabled === null) return $this->disabled;
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * Create a textarea.
     * @param string $id The id of the field.
     * @param bool $resizable Whether it is resizable.
     * @param int $rows The number of rows.
     * @param string[]|string $classes Some classes.
     * @param int|null $max The maximum number of characters.
     * @param string|null $name An optional name.
     * @return TextHtmlView
     */
    public static function create(string $id, bool $resizable = false, int $rows = 3,
                                  array|string $classes = [], int|null $max = null,
                                  string|null $name = null) : TextHtmlView {
        return new TextHtmlView($id, is_null($name) ? $id : $name, $rows, $max, $classes, $resizable);
    }

    /**
     * Get the id.
     * @return string|null
     */
    public function getId() : ?string {
        return $this->id;
    }
}
?>