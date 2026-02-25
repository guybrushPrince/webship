<?php

namespace webship\interoperability;

use ship\protocols\ModifyProtocol;
use webship\core\HtmlView;

/**
 * Interface IClickAway.
 *
 * <p>Supports interoperability so that different implementations can be used that stores somewhere what alerts were
 * clicked already away by users.</p>
 *
 * @package webship
 * @subpackge php/interoperability
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
interface IClickAway {

    /**
     * Determines whether an alert shall be shown, or not.
     * @param string $alertId The alert id.
     * @return bool
     */
    public function shallShow(string $alertId) : bool;

    /**
     * Implementation and effect when there is a specific alert id, which is "clicked away".
     * @param string $alertId The alert to click away.
     * @param HtmlView $closer The closer view.
     * @return ModifyProtocol
     */
    public function doNotShowAnymore(string $alertId, HtmlView $closer) : ModifyProtocol;

}
?>