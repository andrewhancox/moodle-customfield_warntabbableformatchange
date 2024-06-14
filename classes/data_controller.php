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
 * @package customfield_warntabbableformatchange
 * @author Andrew Hancox <andrewdchancox@googlemail.com>
 * @author Open Source Learning <enquiries@opensourcelearning.co.uk>
 * @link https://opensourcelearning.co.uk
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2024, Andrew Hancox
 */

namespace customfield_warntabbableformatchange;

defined('MOODLE_INTERNAL') || die;

class data_controller extends \core_customfield\data_controller {
    public function datafield(): string {
        return 'intvalue';
    }

    public function get_default_value() {
        return 1;
    }

    public function instance_form_definition(\MoodleQuickForm $mform) {
        global $CFG;

        $newformat = optional_param('format', null, PARAM_ALPHANUM);

        if (empty($newformat) || empty($this->get('instanceid'))) {
            return;
        }

        $originalformat = get_course($this->get('instanceid'))->format;

        require_once("$CFG->dirroot/course/format/$newformat/lib.php");
        require_once("$CFG->dirroot/course/format/$originalformat/lib.php");

        $originaltraits = class_uses("format_" . $originalformat);
        $isit_original = !empty($originaltraits) && in_array('local_tabableformathelpers\tabtraits\format', $originaltraits);

        $newtraits = class_uses("format_" . $newformat);
        $isit_new = !empty($newtraits) && in_array('local_tabableformathelpers\tabtraits\format', $newtraits);

        if (empty($isit_original) || ($isit_original && $isit_new)) {
            return;
        }

        $element = $mform->createElement(
            'html',
            \html_writer::div(get_string('warning', 'customfield_warntabbableformatchange'), 'alert alert-warning')
        );
        $formatidx = $mform->_elementIndex['format'];
        $firstelemafterformat = $mform->_elements[$formatidx + 2];

        $mform->insertElementBefore(
            $element,
            $firstelemafterformat->getAttribute('name')
        );
    }
}
