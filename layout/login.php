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
 * A login page layout for the boost theme.
 *
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @copyright based on work by 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Reuse the parent's supported login layouts while preserving EadFlix-specific settings.
require_once("{$CFG->dirroot}/theme/eadtraining/lib.php");

$bodyattributes = $OUTPUT->body_attributes();
$parentlogintheme = theme_eadtraining_default("logintheme", "dark-elegante", '/^[A-Za-z0-9-]+$/');
$loginthemename = theme_eadtraining_default("logintheme", $parentlogintheme,
    '/^[A-Za-z0-9-]+$/', "theme_eadflix");

// Prefer the EadFlix image, retain older EadTraining settings as a fallback.
$loginbackgroundimageurl = theme_eadflix_setting_file_url("loginbackgroundimage");
if (!$loginbackgroundimageurl) {
    $loginbackgroundimageurl = theme_eadtraining_setting_file_url("loginbackgroundimage");
}
if (!$loginbackgroundimageurl) {
    $loginbackgroundimageurl = $OUTPUT->image_url("login/{$loginthemename}", "theme_eadtraining");
}

$templatecontext = [
    "sitename" => format_string($SITE->shortname, true,
        ["context" => context_course::instance(SITEID), "escape" => false]),
    "output" => $OUTPUT,
    "bodyattributes" => $bodyattributes,
    "login_theme" => $loginthemename,
    "footer_show_copywriter" => get_config("theme_eadtraining", "footer_show_copywriter"),
    "languagemenu" => (new \core\output\language_menu($PAGE))->export_for_action_menu($OUTPUT),
    "loginbackgroundimageurl" => $loginbackgroundimageurl->out(false),
];

echo $OUTPUT->render_from_template("theme_eadflix/login", $templatecontext);
