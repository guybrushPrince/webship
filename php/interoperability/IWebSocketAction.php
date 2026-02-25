<?php

namespace webship\interoperability;

/**
 * Interface IWebSocketAction.
 *
 * <p>Webship allows for reacting on web sockets. However, the implementation must be provided in form of an
 * implementation of this interface.</p>
 *
 * @package webship
 * @subpackge php/interoperability
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
interface IWebSocketAction {

    /**
     * Perform an action generating code for the web socket call.
     * @param string $controller The controller.
     * @param string $method The method.
     * @param string|int $action The action.
     * @param array $data The data.
     * @param array $protocol The protocol actions.
     * @return string|null
     */
    public function action(string $controller, string $method, string|int $action, array $data,
                           array $protocol) : string|null;
}
?>