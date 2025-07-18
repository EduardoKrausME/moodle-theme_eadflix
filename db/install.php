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
 * Theme custom Installation.
 *
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Theme_eadflix install function.
 *
 * @return void
 * @throws Exception
 */
function xmldb_theme_eadflix_install() {
    global $DB, $CFG;

    // Profile background image.
    $fs = get_file_storage();
    $filerecord = [
        "component" => "theme_eadflix",
        "contextid" => context_system::instance()->id,
        "userid" => get_admin()->id,
        "filearea" => "background_profile_image",
        "filepath" => "/",
        "itemid" => 0,
        "filename" => "user-modal-background.jpg",
    ];
    $fs->create_file_from_pathname($filerecord, "{$CFG->dirroot}/theme/boost_training/pix/user-modal-background.jpg");
    set_config("background_profile_image", "/user-modal-background.jpg", "theme_eadflix");

    set_config("loginbackgroundimage", "", "theme_eadflix");

    set_config("scsspre", "", "theme_eadflix");
    set_config("scss", "", "theme_eadflix");

    set_config("course_summary", "0", "theme_eadflix");

    set_config("footer_background_color", "", "theme_eadflix");
    set_config("footer_title_1", "", "theme_eadflix");
    set_config("footer_html_1", "", "theme_eadflix");
    set_config("footer_title_2", "", "theme_eadflix");
    set_config("footer_html_2", "", "theme_eadflix");
    set_config("footer_title_3", "", "theme_eadflix");
    set_config("footer_html_3", "", "theme_eadflix");
    set_config("footer_title_4", "", "theme_eadflix");
    set_config("footer_html_4", "", "theme_eadflix");

    set_config("footer_show_copywriter", "1", "theme_eadflix");
}
