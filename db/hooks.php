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
 * Hooks
 *
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\hook\output\before_footer_html_generation;
use core\hook\output\before_html_attributes;

defined('MOODLE_INTERNAL') || die;

$callbacks = [
    [
        "hook" => before_html_attributes::class,
        "callback" => '\theme_eadflix\core_hook_output::before_html_attributes',
    ],
    [
        "hook" => before_footer_html_generation::class,
        "callback" => 'theme_eadflix\core_hook_output::before_footer_html_generation',
    ],
];
