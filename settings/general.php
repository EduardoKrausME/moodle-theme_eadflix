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
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $CFG, $OUTPUT, $PAGE;
require_once("{$CFG->dirroot}/theme/eadflix/lib.php");

$page = new admin_settingpage("theme_eadflix_general", get_string("generalsettings", "theme_boost_training"));
$htmlselect = "<link rel=\"stylesheet\" href=\"{$CFG->wwwroot}/theme/eadflix/scss/colors.css\" />";

$config = theme_eadflix_get_config();
if (!isset($config->startcolor[2])) {
    foreach (theme_eadflix_colors() as $color) {
        $htmlselect .= "\n\n" . $OUTPUT->render_from_template("theme_boost_training/settings/color", [
                "background" => $color,
                "startcolor" => true,
                "color" => $color,
            ]);
    }

    $setting = new admin_setting_configtext("theme_eadflix/startcolor",
        get_string('brandcolor', 'theme_eadflix'),
        get_string('brandcolor_desc', 'theme_boost_training') .
        "<div class='mb-3'>{$htmlselect}</div>",
        "#1a2a6c");
    $PAGE->requires->js_call_amd("theme_boost_training/settings", "minicolors", [$setting->get_id()]);
    $setting->set_updatedcallback("theme_eadflix_change_color");
    $page->add($setting);
} else {
    foreach (theme_eadflix_colors() as $color) {
        $htmlselect .= "\n\n" . $OUTPUT->render_from_template("theme_boost_training/settings/color", [
                "background" => $color,
                "brandcolor" => true,
                "color" => $color,
            ]);
    }

    // We use an empty default value because the default colour should come from the preset.
    $setting = new admin_setting_configtext("theme_eadflix/brandcolor",
        get_string('brandcolor', 'theme_boost'),
        get_string('brandcolor_desc', 'theme_boost_training') .
        "<div class='mb-3'>{$htmlselect}</div>",
        '#1a2a6c');
    $setting->set_updatedcallback("theme_eadflix_change_color");
    $page->add($setting);
    $PAGE->requires->js_call_amd("theme_boost_training/settings", "minicolors", [$setting->get_id()]);
}

// Login Background image setting.
$name = "theme_eadflix/loginbackgroundimage";
$title = get_string("loginbackgroundimage", "theme_boost_training");
$description = get_string("loginbackgroundimage_desc", "theme_boost_training");
$setting = new admin_setting_configstoredfile($name, $title, $description, "loginbackgroundimage");
$setting->set_updatedcallback("theme_reset_all_caches");
$page->add($setting);

$settings->add($page);
