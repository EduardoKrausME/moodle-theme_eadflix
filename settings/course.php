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
 * Course file
 *
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Course settings.
$page = new admin_settingpage("theme_eadflix_course", get_string("coursesettings", "theme_boost_training"));

$setting = new admin_setting_configcheckbox("theme_eadflix/course_summary",
    get_string("course_summary", "theme_boost_training"),
    get_string("course_summary_desc", "theme_boost_training"), 0);
$page->add($setting);

$settings->add($page);
