<?php

/**
 * Copyright © 2013 Geoffroy Aubry <geoffroy.aubry@free.fr>
 *
 * This file is part of Supervisor.
 *
 * Supervisor is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Supervisor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Supervisor.  If not, see <http://www.gnu.org/licenses/>
 * Copyright (c) 2013 Geoffroy Aubry <geoffroy.aubry@free.fr>
 * Licensed under the GNU Lesser General Public License v3 (LGPL version 3).
 *
 * @copyright 2013 Geoffroy Aubry <geoffroy.aubry@free.fr>
 * @license http://www.gnu.org/licenses/lgpl.html
 */



namespace GAubry\Supervisor\Tests;

class ColoredUiTest extends SupervisorTestCase
{
    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_ThrowExceptionWhenUnknownTypeAndNoDefinedType ()
    {
        $this->setExpectedException('RuntimeException', "Unknown display type 'info'!\nAvailable types: .");
        $sMsg = $this->shellCodeCall('CUI_COLORS=(); CUI_displayMsg info', false);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_ThrowExceptionWhenUnknownTypeAndOneDefinedType ()
    {
        $this->setExpectedException('RuntimeException', "Unknown display type 'info'!\nAvailable types: a.");
        $sMsg = $this->shellCodeCall('CUI_COLORS=([a]=b); CUI_displayMsg info', false);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_ThrowExceptionWhenUnknownTypeAndSeveralDefinedTypes ()
    {
        $this->setExpectedException('RuntimeException', "Unknown display type 'info'!\nAvailable types: a, c.");
        $sMsg = $this->shellCodeCall('CUI_COLORS=([a]=b [c]=d [c.bold]=d2 [c.header]=d3); CUI_displayMsg info', false);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_ThrowExceptionWhenUnknownTypeWithMsg ()
    {
        $this->setExpectedException('RuntimeException', "Unknown display type 'info'!\nAvailable types: .");
        $sMsg = $this->shellCodeCall('CUI_COLORS=(); CUI_displayMsg info blabla', false);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_Simple ()
    {
        $sMsg = $this->shellCodeCall('CUI_COLORS=([info]=\'\033[0;36m\'); CUI_displayMsg info bla', false);
        $this->assertEquals('\033[0;36mbla\033[0m', $sMsg);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_SimpleWithMultipleMsg ()
    {
        $sMsg = $this->shellCodeCall('CUI_COLORS=([info]=\'\033[0;36m\'); CUI_displayMsg info bla bla bla', false);
        $this->assertEquals('\033[0;36mbla bla bla\033[0m', $sMsg);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_ThrowExceptionWhenOnlyHeader ()
    {
        $this->setExpectedException('RuntimeException', "Unknown display type 'info'!\nAvailable types: .");
        $sMsg = $this->shellCodeCall('CUI_COLORS=([info.header]=\'\033[0;36m\'); CUI_displayMsg info bla', false);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_WithHeader ()
    {
        $sMsg = $this->shellCodeCall(
            'CUI_COLORS=([info]=\'\033[0;36m\' [info.header]=\'\033[1;36m(i) \'); '
            . 'CUI_displayMsg info bla bla', false
        );
        $this->assertEquals('\033[1;36m(i) \033[0;36mbla bla\033[0m', $sMsg);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_ThrowExceptionWhenOnlyBold ()
    {
        $this->setExpectedException('RuntimeException', "Unknown display type 'info'!\nAvailable types: .");
        $sMsg = $this->shellCodeCall('CUI_COLORS=([info.bold]=\'\033[0;36m\'); CUI_displayMsg info bla', false);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_WithBold ()
    {
        $sMsg = $this->shellCodeCall(
            'CUI_COLORS=([info]=\'\033[0;36m\' [info.bold]=\'\033[1;36m\'); '
            . 'CUI_displayMsg info \"bla<b>haha</b>bla\"', false
        );
        $this->assertEquals('\033[0;36mbla\033[1;36mhaha\033[0;36mbla\033[0m', $sMsg);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_WithMultipleBoldTags ()
    {
        $sMsg = $this->shellCodeCall(
            'CUI_COLORS=([info]=\'\033[0;36m\' [info.bold]=\'\033[1;36m\'); '
            . 'CUI_displayMsg info \"bla<b>haha</b>-<b>Hello!</b>bla<b></b>\"', false
        );
        $this->assertEquals('\033[0;36mbla\033[1;36mhaha\033[0;36m-\033[1;36mHello!\033[0;36mbla\033[1;36m\033[0;36m\033[0m', $sMsg);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_WithBoldTagsButWithoutBold ()
    {
        $sMsg = $this->shellCodeCall(
            'CUI_COLORS=([info]=\'\033[0;36m\'); '
            . 'CUI_displayMsg info \"bla<b>haha</b>bla\"', false
        );
        $this->assertEquals('\033[0;36mblahahabla\033[0m', $sMsg);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_WithMultipleBoldTagsButWithoutBold ()
    {
        $sMsg = $this->shellCodeCall(
            'CUI_COLORS=([info]=\'\033[0;36m\'); '
            . 'CUI_displayMsg info \"bla<b>haha</b>-<b>Hello!</b>bla<b></b>\"', false
        );
        $this->assertEquals('\033[0;36mblahaha-Hello!bla\033[0m', $sMsg);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_WithBoldAndHeader ()
    {
        $sMsg = $this->shellCodeCall(
            'CUI_COLORS=([info]=\'\033[0;36m\' [info.bold]=\'\033[1;36m\' [info.header]=\'\033[1;36m(i) \'); '
            . 'CUI_displayMsg info \"bla<b>haha</b>bla\"', false
        );
        $this->assertEquals('\033[1;36m(i) \033[0;36mbla\033[1;36mhaha\033[0;36mbla\033[0m', $sMsg);
    }

    /**
     * @shcovers inc/coloredUI.sh::CUI_isSet
     * @shcovers inc/coloredUI.sh::CUI_displayMsg
     */
    public function testDisplayMsg_WithBoldAndHeaderAndBackslashes ()
    {
        $sMsg = $this->shellCodeCall(
            'CUI_COLORS=([info]=\'\033[0;36m\' [info.bold]=\'/&\\\' [info.header]=\'/i\\ \'); '
            . 'CUI_displayMsg info \"bla<b>haha</b>bla\"', false
        );
        $this->assertEquals('/i\ \033[0;36mbla/&\haha\033[0;36mbla\033[0m', $sMsg);
    }
}
