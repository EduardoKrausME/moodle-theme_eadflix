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
 * Class core_hook_output
 *
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_eadflix;

use core\hook\output\before_html_attributes;
use Exception;

/**
 * Class core_hook_output
 *
 * @package theme_eadflix
 */
class core_hook_output {

    /**
     * Function before_html_attributes
     *
     * @throws Exception
     */
    public static function before_html_attributes(before_html_attributes $hook): void {
        global $CFG;

        $theme = $CFG->theme;
        if (isset($_SESSION["SESSION"]->theme)) {
            $theme = $_SESSION["SESSION"]->theme;
        }
        if ($theme != "eadflix") {
            return;
        }

        $hook->add_attribute("data-themename", "eadflix");
        $hook->add_attribute("data-background-color", get_config("theme_eadflix", "brandcolor"));
    }

    /**
     * Function before_footer_html_generation
     *
     * @throws Exception
     */
    public static function before_footer_html_generation() {
        global $CFG;

        require_once("{$CFG->dirroot}/theme/boost_training/classes/core_hook_output.php");

        \theme_boost_training\core_hook_output::before_footer_html_generation();
    }
}
