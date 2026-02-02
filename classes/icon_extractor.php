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
 * Icon extractor for "solid background" images.
 *
 * @package   theme_eadflix
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_eadflix;

/**
 * Icon extractor for "solid background" images.
 *
 * Strategy:
 *  - Check only the 4 corners to decide if the background is solid (within tolerance).
 *  - If solid, flood-fill from the borders removing background-like pixels (connected to the edges only).
 *  - Compute content bounding box and crop.
 *  - Optionally force a square output (pad or crop).
 *
 * Requirements:
 *  - PHP GD extension enabled.
 */
class icon_extractor extends \theme_eadtraining\icon_extractor {
}
