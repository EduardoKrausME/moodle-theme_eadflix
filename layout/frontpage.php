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
 * A frontpage based layout for the boost theme.
 *
 * @package   theme_eadflix
 * @copyright 2025 Eduardo Kraus
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE, $OUTPUT, $USER, $DB;

require_once($CFG->libdir . "/behat/lib.php");
require_once($CFG->dirroot . "/course/lib.php");

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

$extraclasses = ["uses-drawers"];

$blockshtml = $OUTPUT->blocks("side-pre");

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = "";
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, "nav-tabs", true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer("core");
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don"t add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$templatecontext = [
    "sitename" => format_string($SITE->shortname, true, ["context" => context_course::instance(SITEID), "escape" => false]),
    "output" => $OUTPUT,
    "sidepreblocks" => $blockshtml,
    "bodyattributes" => $bodyattributes,
    "primarymoremenu" => $primarymenu["moremenu"],
    "secondarymoremenu" => $secondarynavigation ?: false,
    "mobileprimarynav" => $primarymenu["mobileprimarynav"],
    "usermenu" => $primarymenu["user"],
    "langmenu" => $primarymenu["lang"],
    "forceblockdraweropen" => $forceblockdraweropen,
    "regionmainsettingsmenu" => $regionmainsettingsmenu,
    "hasregionmainsettingsmenu" => !empty($regionmainsettingsmenu),
    "overflow" => $overflow,
    "headercontent" => $headercontent,
    "addblockbutton" => $addblockbutton,
];

$config = theme_eadflix_get_config();

$templatecontext["footercount"] = 0;
$templatecontext["footercontents"] = [];
$templatecontext["footer_background_color"] = $config->footer_background_color;
for ($i = 1; $i <= 4; $i++) {
    $footertitle = $config->{"footer_title_{$i}"};
    $footerhtml = $config->{"footer_html_{$i}"};

    if (isset($footerhtml[20])) {
        $templatecontext["footercount"]++;
        $templatecontext["footercontents"][] = [
            "footertitle" => $footertitle,
            "footerhtml" => $footerhtml,
        ];
    }
}
$templatecontext["footer_show_copywriter"] = $config->footer_show_copywriter;

if (isset($USER->editing) && $USER->editing) {
    $sesskey = sesskey();
    $templatecontext["editing"] = true;
    $templatecontext["homemode_form_action"] =
        "{$CFG->wwwroot}/theme/boost_training/_editor/actions.php?action=homemode&chave=editing&sesskey={$sesskey}";
    $templatecontext["homemode_add_action"] =
        "{$CFG->wwwroot}/theme/boost_training/_editor/?action=home&chave=editing&sesskey={$sesskey}";
}

if (isset($config->homemode) && $config->homemode) {
    require_once(__DIR__ . "/../../boost_training/_editor/editor-lib.php");

    $editing = (!isset($USER->editing) || !$USER->editing);
    $lang = $USER->lang ?? $CFG->lang;

    $previewdataid = optional_param("dataid", false, PARAM_INT);
    $cache = \cache::make("theme_boost_training", "frontpage_cache");
    $cachekey = "homemode_pages";
    if ( !$editing && $cache->has($cachekey) && !$previewdataid) {
        $pages = json_decode($cache->get($cachekey));
    } else {
        $where = "local='home' AND lang IN(:lang, 'all')";
        $pages = $DB->get_records_select("theme_boost_training_pages", $where, ['lang' => $lang], "sort ASC");
        $pages = compile_pages($pages);

        if (!$editing && !$previewdataid) {
            $cache->set($cachekey, json_encode($pages));
        }
    }

    $templatecontext["homemode_pages"] = $pages;
    $templatecontext["homemode_status"] = $config->homemode;

    if ($editing) {
        if (count($pages) == 0 && has_capability("moodle/site:config", context_system::instance())) {
            $templatecontext["homemode_page_warningnopages"] = true;
        }
    }
    $PAGE->requires->strings_for_js(["preview"], "theme_boost_training");
    $PAGE->requires->js_call_amd("theme_boost_training/frontpage", "init", [$lang]);
}

echo $OUTPUT->render_from_template("theme_eadflix/frontpage", $templatecontext);
