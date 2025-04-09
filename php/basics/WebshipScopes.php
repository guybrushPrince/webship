<?php

/**
 * Enumeration WebshipScopes.
 *
 * <p>Predefined <i>Ship Scopes</i> that specify specific contexts.</p>
 *
 * @package webship
 * @subpackage php/basics
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class WebshipScopes extends Enumeration {

    public const OPENING_CARD_OPENS = 'opening-cards-open';
    public const OPENING_CARD_CLOSES = 'opening-cards-closes';

    /**
     * @inheritDoc
     */
    public static function getValues() : array {
        return [
            self::OPENING_CARD_OPENS  => 'opening-cards-open',
            self::OPENING_CARD_CLOSES => 'opening-cards-closes'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getNames() : array {
        return [
            'opening-cards-open'   => 'OPENING_CARD_OPENS',
            'opening-cards-closes' => 'OPENING_CARD_CLOSES'
        ];
    }
}
?>