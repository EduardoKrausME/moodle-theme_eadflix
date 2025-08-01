<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Events file
 *
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        "eventname" => '\core\event\course_deleted',
        "callback" => '\theme_eadflix\events\event_observers::process_event',
    ],
    [
        "eventname" => '\core\event\course_updated',
        "callback" => '\theme_eadflix\events\event_observers::process_event',
    ],
    [
        "eventname" => '\core\event\course_created',
        "callback" => '\theme_eadflix\events\event_observers::process_event',
    ],
    [
        "eventname" => '\core\event\config_log_created',
        "callback" => '\theme_eadflix\events\event_observers::process_event',
    ],
    [
        "eventname" => '\core\event\course_module_deleted',
        "callback" => '\theme_eadflix\events\event_observers::course_module_deleted',
    ],
    [
        "eventname" => '\core\event\user_enrolment_created',
        "callback" => '\theme_eadflix\events\event_observers::enrolment',
    ],
    [
        "eventname" => '\core\event\user_enrolment_updated',
        "callback" => '\theme_eadflix\events\event_observers::enrolment',
    ],
];
