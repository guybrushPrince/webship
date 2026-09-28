<?php

namespace webship\buttons;

use ship\interoperability\SLanguage as Language;
use webship\core\HtmlView;

/**
 * View RefreshHtmlView.
 *
 * <p>Represents a simple refresh button.</p>
 * <p><em>This view is never instantiated.</em></p>
 *
 * <code>
 *
 * </code>
 *
 * @title Refresh
 * @category buttons
 * 
 * @package webship
 * @subpackage php/model/buttons
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.4.0
 *
 * @view
 * @examples
 */
class RefreshHtmlView extends HtmlView {

    /**
     * Creates a refresh button but as a standard button.
     * @param int|string $id The id of the button
     * @param string[]|string|null $title Own titles / notices.
     * @param string $image The image path.
     * @param string[]|string $classes (optional) The classes.
     * @return StandardButtonHtmlView
     */
    public static function standard(string|int $id, array|string|null $title = null,
                                    string $image = '/webship/pic/update-arrow.svg',
                                    array|string $classes = []) : StandardButtonHtmlView {
        $button = StandardButtonHtmlView::create('refresh-' . $id, $classes);
        if (!$title) {
            $title = [
                Language::ENGLISH => 'Refresh this view',
                Language::GERMAN => 'Diese Ansicht aktualisieren'
            ];
        } else if (!is_array($title)) $title = [self::getLanguage() => $title];

        foreach ($title as $l => $t) $button->notice($l, $t);

        $button->img($image);
        $button->onlyImg(true);
        return $button;
    }

}

?>