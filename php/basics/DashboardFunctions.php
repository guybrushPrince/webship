<?php

namespace webship\controller;

use ship\controllers\EventAdder;
use ship\interoperability\SLanguage as Language;
use ship\template\TemplateObject;
use webship\protocols\HtmlTemplateProtocol;

/**
 * Class DashboardFunctions.
 *
 * <p>Implements UI functions to interact with dashboards.</p>
 *
 * @see EventAdder
 * @package webship
 * @subpackge php/basics
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
class DashboardFunctions implements EventAdder {

    public const ENVIRONMENT = 'dashboards';

    public const LoadDashboard = 'LoadDashboard';
    public const LoadDashboardUnsafe = 'LoadDashboardUnsafe';

    /**
     * @inheritDoc
     */
    public static function add() : bool {
        HtmlTemplateProtocol::_new(self::LoadDashboard, self::ENVIRONMENT)->
            action(DashboardController::class, 'loadDashboard', [
                'cols' => TemplateObject::_new('cols'),
                'rows' => TemplateObject::_new('rows'),
                'init' => TemplateObject::_new('init')
            ], [
                Language::ENGLISH => 'Load Dashboard ...',
                Language::GERMAN  => 'Lade Dashboard ...'
            ])->
            success()->
                replace(TemplateObject::_new('replace'))->
            parent()->
            error()->
                add(TemplateObject::_new('error'))->
            parent();

        HtmlTemplateProtocol::_new(self::LoadDashboardUnsafe, self::ENVIRONMENT)->
            action(DashboardController::class, 'loadDashboardUnsafe', [
                'cols' => TemplateObject::_new('cols'),
                'rows' => TemplateObject::_new('rows'),
                'init' => TemplateObject::_new('init')
            ], [
                Language::ENGLISH => 'Load Dashboard ...',
                Language::GERMAN  => 'Lade Dashboard ...'
            ])->
            success()->
                replace(TemplateObject::_new('replace'))->
            parent()->
            error()->
                add(TemplateObject::_new('error'))->
            parent();
        
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function environment() : string {
        return self::ENVIRONMENT;
    }
}
?>