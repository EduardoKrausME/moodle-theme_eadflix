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
 * @package   theme_iuna
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Course settings.
$page = new admin_settingpage("theme_iuna_course", get_string("coursesettings", "theme_iuna"));

$setting = new admin_setting_configcheckbox("theme_iuna/course_summary",
    get_string("course_summary", "theme_iuna"),
    get_string("course_summary_desc", "theme_iuna"),
    0);
$page->add($setting);

$options = [
    0 => get_string("course_summary_banner_none", "theme_iuna"),
    1 => get_string("course_summary_banner_simple", "theme_iuna"),
    2 => get_string("course_summary_banner_title", "theme_iuna"),
];
$setting = new admin_setting_configselect("theme_iuna/course_summary_banner",
    get_string("course_summary_banner", "theme_iuna"),
    get_string("course_summary_banner_desc", "theme_iuna"),
    0, $options);
$page->add($setting);

$settings->add($page);
