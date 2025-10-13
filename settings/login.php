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
 * Login file
 *
 * @package   theme_eadtraining
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $CFG, $OUTPUT, $PAGE;
require_once("{$CFG->dirroot}/theme/eadtraining/lib.php");

$page = new admin_settingpage("theme_eadtraining_login",
    get_string("loginsettings", "theme_eadtraining"));

$options = [
    "aurora" => get_string("logintheme_aurora", "theme_eadtraining"),
    "dark-elegante" => get_string("logintheme_dark-elegante", "theme_eadtraining"),
    "selva-canopy" => get_string("logintheme_selva-canopy", "theme_eadtraining"),
    "clean-minimal" => get_string("logintheme_clean-minimal", "theme_eadtraining"),
    "clean-outline" => get_string("logintheme_clean-outline", "theme_eadtraining"),
];
$setting = new admin_setting_configselect("theme_eadtraining/logintheme",
    get_string("logintheme", "theme_eadtraining"),
    get_string("logintheme_desc", "theme_eadtraining"),
    "dark-elegante", $options);
$page->add($setting);

// Login Background image setting.
$setting = new admin_setting_configstoredfile("theme_eadtraining/loginbackgroundimage",
    get_string("loginbackgroundimage", "theme_eadtraining"),
    get_string("loginbackgroundimage_desc", "theme_eadtraining"), "loginbackgroundimage");
$setting->set_updatedcallback("theme_reset_all_caches");
$page->add($setting);

// Small logo file setting.
$setting = new admin_setting_configstoredfile("theme_eadtraining/loginlogo",
    get_string("loginlogo", "theme_eadtraining"),
    get_string("loginlogo_desc", "theme_eadtraining"),
    "loginlogo", 0,
    ["maxfiles" => 1, "accepted_types" => [".jpg", ".jpeg", ".svg", ".png"]]);
$setting->set_updatedcallback("theme_reset_all_caches");
$page->add($setting);

$settings->add($page);
