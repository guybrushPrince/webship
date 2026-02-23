<?php

namespace webship\interoperability;
/**
 * Class Event
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class WEvent {
    const CLICK = 0;
    const ONMOUSEOVER = 1;
    const ONLOAD = 2;
    const ONMOUSEOUT = 3;
    const NEW_TAB = 4;
    const CLOSE_TAB = 5;
    const NEW_TASK = 6;
    const REMOVE_TASK = 7;
    const CLICK_OUTSIDE = 8;
    const ONFOCUSOUT = 9;
    const ONKEYUP = 10;
    const ONFOCUSIN = 11;
    const CONTENTMODIFY = 12;
    const MOVEUP = 13;
    const MOVEDOWN = 14;
    const MOVEDELETE = 15;
    const ONLOADCONTENT = 16;
    const CHANGE = 17;
    const INPUT = 18;
    const SHOW_HINT = 19;
    const HIDE_HINT = 20;
    const CONTEXTMENU = 21;
    const COLUMN = 22;
    const NEW_FILE = 23;
    const DIRTY = 24;
    const TIMED = 25;
    const OPEN_LIST = 26;
    const CLOSE_LIST = 27;
    const NEW_ENTRY = 28;
    const REMOVE_ENTRY = 29;
    const DELETE = 30;
    const OBJECTMODIFY = 31;
    const DRAGSTART = 32;
    const DRAGDROP = 33;
    const VARIABLE_CHANGE = 34;
    const NEXT_STEP = 35;
    const NEXT_DEAD = 36;
    const CLOSE_MODAL = 37;
    const CHANGE_TAB = 38;
    const DELAYED = 45;
    const ON_IMPORT = 46;

    /**
     * The JS event names if available.
     * @var string[]
     */
    public static array $TITLES = [
        0  => 'click',
        1  => 'onmouseover',
        2  => 'onload',
        3  => 'onmouseout',
        4  => 'new-tab',
        5  => 'close-tab',
        6  => 'new-task',
        7  => 'remove-task',
        8  => 'click-outside',
        9  => 'onfocusout',
        10 => 'onkeyup',
        11 => 'onfocusin',
        12 => 'contentmodify',
        13 => 'moveup',
        14 => 'movedown',
        15 => 'movedelete',
        16 => 'onloadcontent',
        17 => 'onchange',
        18 => 'oninput',
        19 => 'showHint',
        20 => 'hideHint',
        21 => 'contextmenu',
        22 => 'column',
        23 => 'newfile',
        24 => 'dirty',
        25 => 'timed',
        26 => 'open-list',
        27 => 'close-list',
        28 => 'new-entry',
        29 => 'remove-entry',
        30 => 'delete',
        31 => 'objectmodify',
        32 => 'dragstart',
        33 => 'dragdrop',
        34 => 'variablechange',
        35 => 'next-step',
        36 => 'next-dead',
        37 => 'close-modal',
        38 => 'change-tab',
        41 => 'job-start',
        42 => 'job-in-progress',
        43 => 'job-done',
        44 => 'job-error',
        45 => 'delayed',
        46 => 'on-import'
    ];

}
?>