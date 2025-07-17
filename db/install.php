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

    // Category.
    $category = $DB->get_record("customfield_category",
        ["id" => intval(@$CFG->theme_customfield_picture)]);
    if (!$category) {
        $category = (object)[
            "name" => get_string("customfield_category_name", "theme_eadflix"),
            "description" => null,
            "descriptionformat" => "0",
            "sortorder" => "0",
            "timecreated" => time(),
            "timemodified" => time(),
            "component" => "core_course",
            "area" => "course",
            "itemid" => "0",
            "contextid" => context_system::instance()->id,
        ];
        $category->id = $DB->insert_record("customfield_category", $category);
        $CFG->theme_customfield_picture = $category->id;
        set_config("theme_customfield_picture", $category->id);
    }

    $field = $DB->get_record("customfield_field", ["shortname" => "show_image_top_course"]);
    if (!$field) {
        $field = [
            "shortname" => "show_image_top_course",
            "name" => get_string("customfield_field_name", "theme_eadflix"),
            "description" => get_string("customfield_field_name_desc", "theme_eadflix"),
            "type" => "select",
            "descriptionformat" => 1,
            "sortorder" => 1,
            "categoryid" => $CFG->theme_customfield_picture,
            "configdata" => json_encode([
                "required" => "0",
                "uniquevalues" => "0",
                "options" => get_string("yes") . "\r\n" . get_string("no"),
                "defaultvalue" => get_string("no"),
                "locked" => "0",
                "visibility" => "0",
            ]),
            "timecreated" => time(),
            "timemodified" => time(),
        ];
        $DB->insert_record("customfield_field", $field);
    }

    $field = $DB->get_record("customfield_field", ["shortname" => "background_course_image"]);
    if (!$field) {
        $field = [
            "shortname" => "background_course_image",
            "name" => get_string("customfield_field_image", "theme_eadflix"),
            "description" => get_string("customfield_field_image_desc", "theme_eadflix"),
            "type" => "picture",
            "descriptionformat" => 1,
            "sortorder" => 2,
            "categoryid" => $CFG->theme_customfield_picture,
            "configdata" => json_encode([
                "required" => "0",
                "uniquevalues" => "0",
                "maximumbytes" => "0",
                "locked" => "0",
                "visibility" => "0",
            ]),
            "timecreated" => time(),
            "timemodified" => time(),
        ];
        $DB->insert_record("customfield_field", $field);
    }

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
