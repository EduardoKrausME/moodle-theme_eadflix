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
 * Theme Settings File
 *
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$page = new admin_settingpage("theme_eadflix_accessibility",
    get_string("settings_accessibility_heading", "theme_eadtraining"));

$url = "{$CFG->wwwroot}/theme/eadtraining/quickstart/?savetheme=eadflix#accessibility";
$setting = new admin_setting_heading("theme_eadtraining_quickstart_accessibility", "",
    get_string("quickstart_settings_link", "theme_eadtraining", $url));
$page->add($setting);

$page->add(new admin_setting_configcheckbox("theme_eadtraining/enable_accessibility",
    get_string("settings_accessibility", "theme_eadtraining"),
    get_string("settings_accessibility_desc", "theme_eadtraining"), 1));

if ($CFG->lang == "pt_br") {
    $page->add(new admin_setting_configcheckbox("theme_eadtraining/enable_vlibras",
        "Habilitar VLibras",
        "", 0));
}
$settings->add($page);
