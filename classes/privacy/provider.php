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

namespace customfield_warntabbableformatchange\privacy;

use core_customfield\data_controller;
use core_customfield\privacy\customfield_provider;
use core_privacy\local\request\writer;

defined('MOODLE_INTERNAL') || die();

class provider implements \core_privacy\local\metadata\null_provider, customfield_provider {

    public static function get_reason() : string {
        return 'privacy:metadata';
    }

    public static function export_customfield_data(data_controller $data, \stdClass $exportdata, array $subcontext) {
        $context = $data->get_context();
        // For text fields we want to apply format_string even to raw value to avoid CSS.
        $exportdata->{$data->datafield()} = $data->export_value();
        writer::with_context($context)
            ->export_data($subcontext, $exportdata);
    }

    public static function before_delete_data(string $dataidstest, array $params, array $contextids) {
    }

    public static function before_delete_fields(string $fieldidstest, array $params, array $contextids) {
    }
}
