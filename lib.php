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
 * Theme functions.
 *
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @copyright based on work by 2016 Frédéric Massart - FMCorz.net
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\output\notification;
use theme_eadflix\thumb_generator;
use theme_eadtraining\admin\setting_scss;

/**
 * Post process the CSS tree.
 *
 * @param string $tree        The CSS tree.
 * @param theme_config $theme The theme config object.
 */
function theme_eadflix_css_tree_post_processor($tree, $theme) {
    $prefixer = new theme_eadflix\autoprefixer($tree);
    $prefixer->prefix();
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 *
 * @return string
 */
function theme_eadflix_get_extra_scss($theme) {
    $content = "";

    // Sets the login background image.
    $loginbackgroundimageurl = $theme->setting_file_url("loginbackgroundimage", "loginbackgroundimage");
    if (!empty($loginbackgroundimageurl)) {
        $content .= "
            body.pagelayout-login #page-wrapper {
                background-image: url('$loginbackgroundimageurl'); background-size: cover;
            }";
    }

    $scsspos = "";
    if (isset($theme->settings->scsspos[5])) {
        $settingscss = new setting_scss("test", "test", "", "");
        $result = $settingscss->validate($theme->settings->scsspos);
        if ($result === true) {
            $scsspos = $theme->settings->scsspos;
        } else {
            $scsspos = "
                #page::before {
                    content: 'theme_eadflix::scsspos Error: {$result}';
                    color: #c00;
                    display: block;
                    padding: 8px 12px;
                    white-space: pre-wrap;
                    background: #FFEB3B;
                    margin: 14px;
                    border-radius: 10px;
                    font-weight: bold;
                } ";
        }
    }

    return "{$content}\n{$scsspos}";
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 *
 * @return bool
 * @throws moodle_exception
 */
function theme_eadflix_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        if (strpos($filearea, "editor_") === 0) {
            $fullpath = sha1("/{$context->id}/theme_eadflix/{$filearea}/{$args[0]}/{$args[1]}");
            $fs = get_file_storage();
            if ($file = $fs->get_file_by_hash($fullpath)) {
                return send_stored_file($file, 0, 0, false, $options);
            }
        } else {
            $theme = theme_config::load("eadflix");
            // By default, theme files must be cache-able by both browsers and proxies.
            if (!array_key_exists("cacheability", $options)) {
                $options["cacheability"] = "public";
            }
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        }
        send_file_not_found();
    } else if ($context->contextlevel == CONTEXT_MODULE) {
        $fs = get_file_storage();
        $fullpath = sha1("/{$context->id}/theme_eadflix/{$filearea}/{$args[0]}/{$args[1]}");
        if (!$file = $fs->get_file_by_hash($fullpath)) {
            return false;
        }
        if ($filearea == "theme_eadflix_customimage" || $filearea == "theme_eadflix_customicon") {
            $thumb = (new thumb_generator())
                ->set_height(($filearea == 'theme_eadflix_customicon') ? 50 : 150)
                ->set_cache_filearea("{$filearea}_thumb")
                ->set_cache_itemid($args[0])
                ->get_or_create($file, $context);
            if ($thumb) {
                return send_stored_file($thumb, 0, 0, false, $options);
            }
        }

        // Fallback: image original.
        return send_stored_file($file, 0, 0, false, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Get the current user preferences that are available
 *
 * @return array[]
 */
function theme_eadflix_user_preferences(): array {
    return [
        "drawer-open-block" => [
            "type" => PARAM_BOOL,
            "null" => NULL_NOT_ALLOWED,
            "default" => false,
            "permissioncallback" => [core_user::class, "is_current_user"],
        ],
        "drawer-open-index" => [
            "type" => PARAM_BOOL,
            "null" => NULL_NOT_ALLOWED,
            "default" => true,
            "permissioncallback" => [core_user::class, "is_current_user"],
        ],
    ];
}

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 *
 * @return string
 */
function theme_eadflix_get_main_scss_content($theme) {
    global $CFG;
    return file_get_contents("{$CFG->dirroot}/theme/eadflix/scss/style.scss");
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 *
 * @return string
 * @throws Exception
 */
function theme_eadflix_get_pre_scss($theme) {
    global $CFG;

    $primarycolorscss = "";
    $brandcolor = get_config("theme_boost", "brandcolor");
    if (isset($brandcolor[3]) && preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $brandcolor)) {
        $primarycolorscss = "\$primary: {$brandcolor};\n";
    }
    $courseid = optional_param("courseid", 0, PARAM_INT);
    if ($courseid) {
        $coursecolor = get_config("theme_eadflix", "override_course_color_{$courseid}");
        if (isset($coursecolor[3]) && preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $coursecolor)) {
            $primarycolorscss = "\$primary: {$coursecolor};\n";
        }
    }
    $scss = $primarycolorscss;

    if ($topscrollbackgroundcolor = get_config("theme_eadflix", "top_scroll_background_color")) {
        $scss .= "\$top_scroll_background_color: {$topscrollbackgroundcolor};\n";
    }

    $callbacks = get_plugins_with_function("krausthemes__get_pre_scss");
    foreach ($callbacks as $plugins) {
        foreach ($plugins as $callback) {
            if ($newscss = $callback()) {
                $scss = $newscss;
            }
        }
    }

    // Prepend pre-scss.
    if (isset($theme->settings->scsspre[5])) {
        $settingscss = new setting_scss("test", "test", "", "");
        $result = $settingscss->validate($theme->settings->scsspre);
        if ($result === true) {
            $scss .= $theme->settings->scsspre;
        } else {
            $scss .= "
                #page::before {
                    content: 'theme_eadflix::scsspre Error: {$result}';
                    color: #c00;
                    display: block;
                    padding: 8px 12px;
                    white-space: pre-wrap;
                    background: #FFEB3B;
                    margin: 14px;
                    border-radius: 10px;
                    font-weight: bold;
                } ";
        }
    }

    return $scss;
}

/**
 * Function theme_eadflix_progress_content
 *
 * @return array
 * @throws coding_exception
 */
function theme_eadflix_progress_content() {
    global $USER, $COURSE, $SESSION;

    $completion = new completion_info($COURSE);

    // First, let's make sure completion is enabled.
    if (!$completion->is_enabled()) {
        return ["isprogress" => false];
    }

    if (!$completion->is_tracked_user($USER->id)) {
        $SESSION->notifications[] = (object) [
            "message" => get_string("notenrolledincourse", "theme_eadflix"),
            "type" => notification::NOTIFY_WARNING,
        ];
        return ["isprogress" => false];
    }

    // Before we check how many modules have been completed see if the course has.
    if ($completion->is_course_complete($USER->id)) {
        return [
            "isprogress" => true,
            "progress" => 100,
        ];
    }

    // Get the number of modules that support completion.
    $modules = $completion->get_activities();
    $count = count($modules);
    if (!$count) {
        return ["isprogress" => false];
    }

    // Get the number of modules that have been completed.
    $completed = 0;
    foreach ($modules as $module) {
        $data = $completion->get_data($module, true, $USER->id);
        if (($data->completionstate == COMPLETION_INCOMPLETE) || ($data->completionstate == COMPLETION_COMPLETE_FAIL)) {
            $completed += 0;
        } else {
            $completed += 1;
        };
    }

    return [
        "isprogress" => true,
        "progress" => intval(($completed / $count) * 100),
        "progress_completed" => $completed,
        "progress_count" => $count,
    ];
}

/**
 * Function theme_eadflix_setting_file_url
 *
 * @param $setting
 *
 * @return bool|moodle_url
 *
 * @throws dml_exception
 */
function theme_eadflix_setting_file_url($setting) {
    $filepath = get_config("theme_eadflix", $setting);
    if (!$filepath) {
        return false;
    }
    $syscontext = context_system::instance();

    $url = moodle_url::make_pluginfile_url($syscontext->id, "theme_eadflix", $setting, 0, "/", $filepath);

    return $url;
}

/**
 * List colors.
 *
 * @return array
 */
function theme_eadflix_colors() {
    return [
        "#1f3b9b", // Azul Escuro Luminoso.
        "#4c6aff", // Azul Neon Suave.
        "#00aaff", // Azul Elétrico.
        "#00bfff", // Azul Neon.
        "#80aaff", // Azul Claro Vibrante.
        "#00ffff", // Ciano Elétrico.
        "#33ffc1", // Verde Claro Neon.
        "#00ffd5", // Verde Azulado Neon.
        "#66ffcc", // Menta Neon.
        "#39ff14", // Verde Laser.
        "#ccff00", // Verde Lima Neon.
        "#ffcc00", // Amarelo Neon.
        "#ffd966", // Amarelo Claro.
        "#ffde59", // Amarelo Solar.
        "#ff9933", // Laranja Neon Vintage.
        "#ffaa00", // Laranja Neon.
        "#ff704d", // Laranja Claro Neon.
        "#ff1a1a", // Vermelho Brilhante.
        "#ff0033", // Vermelho Neon.
        "#ff004f", // Vermelho Fúcsia.
        "#ff3399", // Rosa Neon.
        "#ff6ec7", // Rosa Chiclete Neon.
        "#ff33ff", // Magenta Fluorescente.
        "#ff66f2", // Rosa Neon Suave.
        "#ff99cc", // Rosa Claro Neon.
        "#ffccff", // Lavanda Neon.
        "#7b61ff", // Roxo Neon Claro.
        "#9900ff", // Roxo Neon Intenso.
        "#b84dff", // Roxo Neon Suave.
    ];
}

/**
 * theme_eadflix_coursemodule_standard_elements
 *
 * @param moodleform_mod $formwrapper The moodle quickforms wrapper object.
 * @param MoodleQuickForm $mform The actual form object (required to modify the form).
 *
 * @throws coding_exception
 */
function theme_eadflix_coursemodule_standard_elements(&$formwrapper, $mform) {
    require_once(__DIR__ . "/../eadtraining/lib.php");
    theme_eadtraining_coursemodule_standard_elements($formwrapper, $mform);
}

/**
 * Hook the add/edit of the course module.
 *
 * @param stdClass $data Data from the form submission.
 * @param stdClass $course The course.
 *
 * @return stdClass
 *
 * @throws coding_exception
 */
function theme_eadflix_coursemodule_edit_post_actions($data, $course) {
    require_once(__DIR__ . "/../eadtraining/lib.php");
    return theme_eadtraining_coursemodule_edit_post_actions($data, $course);
}

/**
 * theme_eadflix_get_config
 *
 * @return object
 * @throws dml_exception
 */
function theme_eadflix_get_config() {
    $configboosteadtraining = get_config("theme_eadtraining");
    $configeadflix = get_config("theme_eadflix");
    return (object)array_replace((array)$configboosteadtraining, (array)$configeadflix);
}
