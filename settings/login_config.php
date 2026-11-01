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
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $CFG, $OUTPUT, $PAGE, $SITE;
require_once("{$CFG->dirroot}/theme/eadtraining/lib.php");

$page = new admin_settingpage("theme_eadflix_login",
    get_string("loginsettings", "theme_eadtraining"));

$options = [
    "aurora" => get_string("logintheme_aurora", "theme_eadtraining"),
    "dark-elegante" => get_string("logintheme_dark-elegante", "theme_eadtraining"),
    "selva-canopy" => get_string("logintheme_selva-canopy", "theme_eadtraining"),
    "clean-minimal" => get_string("logintheme_clean-minimal", "theme_eadtraining"),
    "clean-outline" => get_string("logintheme_clean-outline", "theme_eadtraining"),
    "glassmorphism" => get_string("logintheme_glassmorphism", "theme_eadtraining"),
    "serenity-med-blue" => get_string("logintheme_serenity-med-blue", "theme_eadtraining"),
    "serenity-med-red" => get_string("logintheme_serenity-med-red", "theme_eadtraining"),
];
$mustachecontext = (object)[];
// The EadFlix logo takes priority, followed by any pre-existing EadTraining logo.
$url = theme_eadflix_setting_file_url("loginlogo");
if (!$url) {
    $url = theme_eadtraining_setting_file_url("loginlogo");
}
if (!$url) {
    $url = $OUTPUT->get_logo_url();
}
$mustachecontext->logourl = $url ? $url->out(false) : null;
$mustachecontext->sitename = format_string($SITE->fullname);
$mustachecontext->themes = [];
foreach ($options as $loginthemename => $option) {
    $mustachecontext->themes[] = [
        "login_theme" => $loginthemename,
        "loginbackgroundimageurl" => $OUTPUT->image_url("login/{$loginthemename}", "theme_eadtraining")->out(false),
    ];
}
$htmldescextra = $OUTPUT->render_from_template("theme_eadtraining/settings/login", $mustachecontext);
$htmldescextra = preg_replace('/\s+/', ' ', $htmldescextra);

$setting = new admin_setting_configselect("theme_eadflix/logintheme",
    get_string("logintheme", "theme_eadtraining"),
    get_string("logintheme_desc", "theme_eadtraining") . $htmldescextra,
    theme_eadtraining_default("logintheme", "dark-elegante", '/^[A-Za-z0-9-]+$/'), $options);
$page->add($setting);

// Login Background image setting.
$setting = new admin_setting_configstoredfile("theme_eadflix/loginbackgroundimage",
    get_string("loginbackgroundimage", "theme_eadtraining"),
    get_string("loginbackgroundimage_desc", "theme_eadtraining"), "loginbackgroundimage");
$setting->set_updatedcallback("theme_reset_all_caches");
$page->add($setting);

// Small logo file setting.
$setting = new admin_setting_configstoredfile("theme_eadflix/loginlogo",
    get_string("loginlogo", "theme_eadtraining"),
    get_string("loginlogo_desc", "theme_eadtraining"),
    "loginlogo", 0,
    ["maxfiles" => 1, "accepted_types" => [".jpg", ".jpeg", ".svg", ".png"]]);
$setting->set_updatedcallback("theme_reset_all_caches");
$page->add($setting);

$settings->add($page);
