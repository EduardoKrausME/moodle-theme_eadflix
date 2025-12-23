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
 * General file
 *
 * @package   theme_iuna
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $CFG, $OUTPUT, $PAGE;
require_once("{$CFG->dirroot}/theme/iuna/lib.php");

$page = new admin_settingpage("theme_iuna_general",
    get_string("generalsettings", "theme_eadtraining"));

$url = "{$CFG->wwwroot}/theme/eadtraining/quickstart/?savetheme=iuna#brandcolor";
$setting = new admin_setting_heading("theme_iuna_quickstart_brandcolor", "",
    get_string("quickstart_settings_link", "theme_eadtraining", $url));
$page->add($setting);

$htmlselect = "<link rel=\"stylesheet\" href=\"{$CFG->wwwroot}/theme/eadtraining/scss/colors.css\" />";
$htmlselect .= "\n\n" . $OUTPUT->render_from_template("theme_eadtraining/settings/colors", [
        "brandcolor" => true,
        "colors" => theme_iuna_colors(),
        "defaultcolor" => theme_eadtraining_default("brandcolor", "#1a2a6c", "theme_iuna"),
    ]);

// We use an empty default value because the default colour should come from the preset.
$setting = new admin_setting_configtext("theme_iuna/brandcolor",
    get_string('brandcolor', 'theme_boost'),
    get_string('brandcolor_desc', 'theme_eadtraining') .
    $htmlselect,
    '#1a2a6c');
$page->add($setting);
$PAGE->requires->js_call_amd("theme_eadtraining/settings", "minicolors", [$setting->get_id()]);

// Login Background image setting.
$setting = new admin_setting_configstoredfile("theme_iuna/loginbackgroundimage",
    get_string("loginbackgroundimage", "theme_eadtraining"),
    get_string("loginbackgroundimage_desc", "theme_eadtraining"),
    "loginbackgroundimage");
$setting->set_updatedcallback("theme_reset_all_caches");
$page->add($setting);

$settings->add($page);
