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
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Course settings.
$page = new admin_settingpage("theme_eadflix_course", get_string("coursesettings", "theme_boost_training"));

$url = "{$CFG->wwwroot}/theme/boost_training/quickstart/?savetheme=eadflix#courses";
$setting = new admin_setting_heading("theme_boost_training_quickstart_courses", "",
    get_string("quickstart_settings_link", "theme_boost_training", $url));
$page->add($setting);

$options = [
    0 => get_string("course_summary_none", "theme_boost_training"),
    1 => get_string("course_summary_simple", "theme_boost_training"),
    2 => get_string("course_summary_banner", "theme_boost_training"),
];
$setting = new admin_setting_configselect("theme_boost_training/course_summary",
    get_string("course_summary", "theme_boost_training"),
    get_string("course_summary_desc", "theme_boost_training"),
    0, $options);
$page->add($setting);

$options = [
    0 => get_string("course_summary_banner_down", "theme_boost_training"),
    1 => get_string("course_summary_banner_under", "theme_boost_training"),
];
$setting = new admin_setting_configselect("theme_boost_training/course_summary_banner_position",
    get_string("course_summary_banner_default", "theme_boost_training"),
    get_string("course_summary_banner_default_desc", "theme_boost_training"),
    0, $options);
$page->add($setting);

$setting = new admin_setting_configcheckbox("theme_boost_training/svg_animate",
    get_string("svg_animate", "theme_boost_training"),
    get_string("svg_animate_desc", "theme_boost_training"), 0);
$page->add($setting);

$settings->add($page);
