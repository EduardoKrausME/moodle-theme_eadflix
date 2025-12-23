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
 * @package   theme_iuna
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @copyright based on work by 2016 Frédéric Massart - FMCorz.net
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_boost\admin\setting_scss;

/**
 * Post process the CSS tree.
 *
 * @param string $tree        The CSS tree.
 * @param theme_config $theme The theme config object.
 */
function theme_iuna_css_tree_post_processor($tree, $theme) {
    $prefixer = new theme_iuna\autoprefixer($tree);
    $prefixer->prefix();
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 *
 * @return string
 */
function theme_iuna_get_extra_scss($theme) {
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
                    content: 'theme_iuna::scsspos Error: {$result}';
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
function theme_iuna_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        if (strpos($filearea, "editor_") === 0) {
            $fullpath = sha1("/{$context->id}/theme_iuna/{$filearea}/{$args[0]}/{$args[1]}");
            $fs = get_file_storage();
            if ($file = $fs->get_file_by_hash($fullpath)) {
                return send_stored_file($file, 0, 0, false, $options);
            }
        } else {
            $theme = theme_config::load("iuna");
            // By default, theme files must be cache-able by both browsers and proxies.
            if (!array_key_exists("cacheability", $options)) {
                $options["cacheability"] = "public";
            }
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        }
        send_file_not_found();
    } else if ($context->contextlevel == CONTEXT_MODULE) {
        $fullpath = sha1("/{$context->id}/theme_iuna/{$filearea}/{$args[0]}/{$args[1]}");
        $fs = get_file_storage();
        if ($file = $fs->get_file_by_hash($fullpath)) {
            return send_stored_file($file, 0, 0, false, $options);
        }
    } else {
        send_file_not_found();
    }
}

/**
 * Get the current user preferences that are available
 *
 * @return array[]
 */
function theme_iuna_user_preferences(): array {
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
function theme_iuna_get_main_scss_content($theme) {
    global $CFG;
    return file_get_contents("{$CFG->dirroot}/theme/iuna/scss/style.scss");
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 *
 * @return string
 * @throws Exception
 */
function theme_iuna_get_pre_scss($theme) {
    global $CFG;
    require_once("{$CFG->dirroot}/theme/boost/lib.php");
    $scss = theme_boost_get_pre_scss($theme);
    $configurable = [
        // Config key => [variableName, ...].
        "brandcolor" => ["primary"],
    ];

    $configboost = theme_iuna_get_config();
    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($configboost->{$configkey}) ? $configboost->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function ($target) use (&$scss, $value) {
            $scss .= "\${$target}: {$value};\n";
        }, (array)$targets);
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
                    content: 'theme_iuna::scsspre Error: {$result}';
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
 * Function theme_iuna_setting_file_url
 *
 * @param $setting
 *
 * @return bool|moodle_url
 *
 * @throws dml_exception
 */
function theme_iuna_setting_file_url($setting) {
    global $CFG;

    $filepath = get_config("theme_iuna", $setting);
    if (!$filepath) {
        return false;
    }
    $syscontext = context_system::instance();

    $url = moodle_url::make_file_url(
        "$CFG->wwwroot/pluginfile.php",
        "/{$syscontext->id}/theme_iuna/{$setting}/0{$filepath}");

    return $url;
}

/**
 * List colors.
 *
 * @return array
 */
function theme_iuna_colors() {
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
 * theme_iuna_coursemodule_standard_elements
 *
 * @param moodleform_mod $formwrapper The moodle quickforms wrapper object.
 * @param MoodleQuickForm $mform The actual form object (required to modify the form).
 *
 * @throws coding_exception
 */
function theme_iuna_coursemodule_standard_elements(&$formwrapper, $mform) {
    require_once(__DIR__ . "/../boost/lib.php");
    theme_boost_coursemodule_standard_elements($formwrapper, $mform);
}

/**
 * Hook the add/edit of the course module.
 *
 * @param moodleform $data Data from the form submission.
 * @param stdClass $course The course.
 *
 * @return moodleform
 *
 * @throws coding_exception
 */
function theme_iuna_coursemodule_edit_post_actions($data, $course) {
    require_once(__DIR__ . "/../boost/lib.php");
    return theme_boost_coursemodule_edit_post_actions($data, $course);
}

/**
 * theme_iuna_get_config
 *
 * @return object
 * @throws dml_exception
 */
function theme_iuna_get_config() {
    $configboost = get_config("theme_boost");
    $configiuna = get_config("theme_iuna");
    return (object)array_replace((array)$configboost, (array)$configiuna);
}

/**
 * Return the configured value or a default.
 *
 * @param string $setting
 * @param mixed $default
 * @param string $component
 *
 * @return mixed
 */
function theme_iuna_default(string $setting, $default, string $component = "theme_iuna") {
    $value = get_config($component, $setting);
    if ($value === false || $value === null || $value === "") {
        return $default;
    }
    return $value;
}

/**
 * Provide additional template context for progress info.
 *
 * @return array
 */
function theme_iuna_progress_content(): array {
    return [];
}
