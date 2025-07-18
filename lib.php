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
 * @package    theme_eadflix
 * @copyright  2016 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
            body.pagelayout-login #page {
                background-image: url('$loginbackgroundimageurl'); background-size: cover;
            }";
    }

    // Always return the background image with the scss when we have it.
    return !empty($theme->settings->scss) ? "{$theme->settings->scss}  \n  {$content}" : $content;
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
        $fullpath = sha1("/{$context->id}/theme_eadflix/{$filearea}/{$args[0]}/{$args[1]}");
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
 * Get compiled css.
 *
 * @return string compiled css
 */
function theme_eadflix_get_precompiled_css() {
    global $CFG;
    return file_get_contents("{$CFG->dirroot}/theme/eadflix/scss/style.css");
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 *
 * @return string
 */
function theme_eadflix_get_pre_scss($theme) {
    $scss = "";
    $configurable = [
        // Config key => [variableName, ...].
        "brandcolor" => ["primary"],
    ];

    $configboost = theme_eadflix_get_config();
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
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}


/**
 * Function theme_eadflix_setting_file_url
 *
 * @param $setting
 *
 * @return bool|\core\url
 *
 * @throws dml_exception
 */
function theme_eadflix_setting_file_url($setting) {
    global $CFG;

    $filepath = get_config("theme_eadflix", $setting);
    if (!$filepath) {
        return false;
    }
    $itemid = theme_get_revision();
    $syscontext = context_system::instance();

    $url = moodle_url::make_file_url(
        "$CFG->wwwroot/pluginfile.php",
        "/{$syscontext->id}/theme_eadflix/{$setting}/{$itemid}{$filepath}");

    return $url;
}

/**
 * Change color.
 *
 * @throws dml_exception
 */
function theme_eadflix_change_color() {
    $config = theme_eadflix_get_config();
    $configboost = get_config("theme_boost");

    if (isset($config->startcolor[5])) {
        $brandcolor = $config->startcolor;
    } else {
        $brandcolor = $configboost->brandcolor;
    }

    set_config("startcolor", "#000", "theme_eadflix");
    set_config("footer_background_color", $brandcolor, "theme_eadflix");

    theme_reset_all_caches();
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
    require_once(__DIR__ . "/../boost_training/lib.php");
    theme_boost_training_coursemodule_standard_elements($formwrapper, $mform);
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
function theme_eadflix_coursemodule_edit_post_actions($data, $course) {
    require_once(__DIR__ . "/../boost_training/lib.php");
    return theme_boost_training_coursemodule_edit_post_actions($data, $course);
}

/**
 * theme_eadflix_get_config
 *
 * @return object
 * @throws dml_exception
 */
function theme_eadflix_get_config(){
    $configboosttraining = get_config("theme_boost_training");
    $configeadflix = get_config("theme_eadflix");
    return (object)array_replace((array)$configboosttraining, (array)$configeadflix);
}
