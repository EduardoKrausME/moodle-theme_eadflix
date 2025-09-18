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
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $PAGE, $CFG, $OUTPUT;
require_once("{$CFG->dirroot}/theme/eadflix/lib.php");

// Footer section.
$page = new admin_settingpage("theme_eadflix_footer",
    get_string("footersettings", "theme_eadtraining"));

$url = "{$CFG->wwwroot}/theme/eadtraining/quickstart/?savetheme=eadflix#footer";
$setting = new admin_setting_heading("theme_eadtraining_quickstart_footer", "",
    get_string("quickstart_settings_link", "theme_eadtraining", $url));
$page->add($setting);

$htmlselect = "<link rel=\"stylesheet\" href=\"{$CFG->wwwroot}/theme/eadtraining/scss/colors.css\" />";
$htmlselect .= "\n\n" . $OUTPUT->render_from_template("theme_eadtraining/settings/colors", [
        "footercolor" => true,
        "colors" => theme_eadflix_colors(),
        "defaultcolor" => theme_eadtraining_default("brandcolor", "#1a2a6c", "theme_eadflix"),
        "defaultcolorfooter" => theme_eadtraining_default("footer_background_color", "#1a2a6c", "theme_eadflix"),
    ]);

$setting = new admin_setting_configtext("theme_eadflix/footer_background_color",
    get_string("footer_background_color", "theme_eadtraining"),
    get_string("footer_background_color_desc", "theme_eadtraining") . $htmlselect,
    "#1a2a6c");
$setting->set_updatedcallback("theme_reset_all_caches");
$PAGE->requires->js_call_amd("theme_eadtraining/settings", "minicolors", [$setting->get_id()]);
$page->add($setting);

$setting = new admin_setting_heading("theme_eadflix_footer_heading_description",
    get_string("footer_heading_description_title", "theme_eadtraining"),
    get_string("footer_heading_description_desc", "theme_eadtraining"));
$page->add($setting);

for ($i = 1; $i <= 4; $i++) {

    $setting = new admin_setting_heading("theme_eadflix_footer_heading_{$i}",
        get_string("footer_heading", "theme_eadtraining", $i), "");
    $page->add($setting);

    $setting = new admin_setting_configtext("theme_eadtraining/footer_title_{$i}",
        get_string("footer_title", "theme_eadtraining", $i),
        get_string("footer_title_desc", "theme_eadtraining", $i), "");
    $page->add($setting);

    $setting = new admin_setting_confightmleditor("theme_eadtraining/footer_html_{$i}",
        get_string("footer_html", "theme_eadtraining", $i),
        get_string("footer_html_desc", "theme_eadtraining", $i), "");
    $page->add($setting);
}

$setting = new admin_setting_heading('theme_eadflix_footerblock_copywriter',
    get_string('footer_copywriter', 'theme_eadtraining'), '');
$page->add($setting);

$setting = new admin_setting_configcheckbox('theme_eadtraining/footer_show_copywriter',
    get_string('footer_show_copywriter', 'theme_eadtraining'),
    get_string('footer_show_copywriter_desc', 'theme_eadtraining'), 1);
$page->add($setting);

$settings->add($page);
