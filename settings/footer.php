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
 * Footer file
 *
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $PAGE, $CFG, $OUTPUT;
require_once("{$CFG->dirroot}/theme/eadflix/lib.php");

// Footer section.
$page = new admin_settingpage("theme_eadflix_footer", get_string("footersettings", "theme_boost_training"));

$htmlselect = "<link rel=\"stylesheet\" href=\"{$CFG->wwwroot}/theme/eadflix/scss/colors.css\" />";
foreach (theme_eadflix_colors() as $color) {
    $htmlselect .= "\n\n" . $OUTPUT->render_from_template("theme_boost_training/settings/color", [
            "background" => $color,
            "footercolor" => true,
            "color" => $color,
        ]);
}

$setting = new admin_setting_configtext("theme_eadflix/footer_background_color",
    get_string("footer_background_color", "theme_boost_training"),
    get_string("footer_background_color_desc", "theme_boost_training") . $htmlselect,
    "#1a2a6c");
$setting->set_updatedcallback("theme_reset_all_caches");
$PAGE->requires->js_call_amd("theme_boost_training/settings", "minicolors", [$setting->get_id()]);
$page->add($setting);

$setting = new admin_setting_heading("theme_eadflix_footer_heading_description",
    get_string("footer_heading_description_title", "theme_boost_training"),
    get_string("footer_heading_description_desc", "theme_boost_training"));
$page->add($setting);

for ($i = 1; $i <= 4; $i++) {

    $setting = new admin_setting_heading("theme_eadflix_footer_heading_{$i}",
        get_string("footer_heading", "theme_eadflix", $i), "");
    $page->add($setting);

    $setting = new admin_setting_configtext("theme_eadflix/footer_title_{$i}",
        get_string("footer_title", "theme_boost_training", $i),
        get_string("footer_title_desc", "theme_boost_training", $i), "");
    $page->add($setting);

    $setting = new admin_setting_confightmleditor("theme_eadflix/footer_html_{$i}",
        get_string("footer_html", "theme_boost_training", $i),
        get_string("footer_html_desc", "theme_boost_training", $i), "");
    $page->add($setting);
}

$setting = new admin_setting_heading('theme_eadflix_footerblock_copywriter',
    get_string('footer_copywriter', 'theme_boost_training'), '');
$page->add($setting);

$setting = new admin_setting_configcheckbox('theme_eadflix/footer_show_copywriter',
    get_string('footer_show_copywriter', 'theme_boost_training'),
    get_string('footer_show_copywriter_desc', 'theme_boost_training'), 1);
$page->add($setting);

$settings->add($page);
