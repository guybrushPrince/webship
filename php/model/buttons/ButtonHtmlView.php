<?php

namespace webship\buttons;

use ship\views\View;
use webship\core\HtmlView;
use webship\WebshipTools;

/**
 * View ButtonHtmlView.
 *
 * <p>A very basic button that can be specialized by more fancy buttons.</p>
 * <img src="http://localhost/coast/webship/modeldoc/button.png" />
 *
 * <code>
 * (new ButtonHtmlView('example', 'button'))->texts('Press me!')
 * </code>
 *
 * @package webship
 * @subpackge php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.3.0
 *
 * @view
 * @examples
 *     (new ButtonHtmlView('example', 'button'))->texts('Press me!')
 */
class ButtonHtmlView extends HtmlView {

    /**
     * An id.
     * @var string|null|false
     */
    private string|null|false $id;

    /**
     * The type
     * @var string
     */
    private string $type;

    /**
     * Set the button disabled or not.
     * @var bool
     */
    private bool $disabled = false;

    /**
     * ButtonHtmlView constructor.
     * @param string|false $id The id.
     * @param string $type The type.
     * @param string[]|string $classes Some classes.
     */
    public function __construct(string|null|false $id, string $type, string|array $classes = []) {
        $classes = is_array($classes) ? $classes : array($classes);
        parent::__construct(array_merge(array('button'), $classes));
        $this->id = $id;
        $this->type = $type;

        self::addCSS();
    }

    /**
     * @inheritDoc
     */
    public static function addCSS() : void {
        self::addStyle(WebshipTools::css('button', '', 'box-sizing: border-box', 'padding: 5px',
            'height: 50px', 'width: 100%', 'font-family: Roboto Condensed, Roboto, HelveticaNeue, sans-serif',
            'font-size: 16px'));
    }

    /**
     * @inheritDoc
     */
    public function generateOpeningTag() : string {
        return '<button class="' . implode(' ', array_merge($this->classes, array($this->id()))) . '"' .
            ($this->id ? ' id="' . $this->id . '"' : '') . ' type="' . $this->type . '"' .
            ($this->notices ? ' title="' . (array_key_exists(View::getLanguage(), $this->notices) ?
                    $this->notices[View::getLanguage()] :
                    array_pop($this->notices)
                ) . '"' : '') .
            ($this->disabled ? ' disabled' : '') .
            ($this->localStyles ? ' style="' .
                str_replace('"', "'", implode(';', $this->localStyles)) . '"' : '') . '>';
    }

    /**
     * @inheritDoc
     */
    public function generateClosingTag() : string {
        return '</button>';
    }

    /**
     * Set disabled.
     * @param bool $disabled The new value.
     * @return $this
     */
    public function disabled(bool $disabled) : ButtonHtmlView {
        $this->disabled = $disabled;
        return $this;
    }
}
?>