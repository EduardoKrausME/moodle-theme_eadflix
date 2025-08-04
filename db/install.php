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
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Theme_eadflix install function.
 *
 * @return void
 * @throws Exception
 */
function xmldb_theme_eadflix_install() {
    set_config("brandcolor", "#1f3b9b", "theme_eadflix");
    set_config("loginbackgroundimage", "", "theme_eadflix");

    set_config("scsspre", "", "theme_eadflix");
    set_config("scss", "", "theme_eadflix");

    set_config("course_summary", "0", "theme_eadflix");

    set_config("footer_background_color", "", "theme_eadflix");
}
