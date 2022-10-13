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
 * usagemonitor external get_data class
 *
 * @package    local_usagemonitor
 * @copyright  2022 DNE - Ministere de l'Education Nationale
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_usagemonitor\external;

require_once("$CFG->libdir/externallib.php");

defined('MOODLE_INTERNAL') || die();

class get_data extends \external_api {

    public static function get_data($pluginname, $extradata = null) {
        $res = new \stdClass();
        $res->data = null;
        $res->error = false;
        $res->errormsg = '';
        
        try {
            if ($extradata) {
                $extradataDecoded = self::decode($extradata); 
            } else {
                $extradataDecoded = null;
            }
            $res->data = self::encode(\local_usagemonitor\plugininfo\usagemonitordata::get_data($pluginname, $extradataDecoded));
        } catch (\Exception $e) {
            $res->error = true;
            $res->errormsg = $e->getMessage();
        }
        
        return $res;
    }

    public static function get_data_parameters() {
        return new \external_function_parameters(
            array(
                'pluginname' => new \external_value(PARAM_TEXT, 'Data subplugin name'),
                'extradata' => new \external_value(PARAM_RAW, 'Extra data that will be passed to the getdata function, must be base64 with serialisation', VALUE_OPTIONAL),
            )
        );
    }

    public static function get_data_returns() {
        return new \external_single_structure(
            array(
                'error' => new \external_value(PARAM_BOOL, ''),
                'errormsg' => new \external_value(PARAM_TEXT, ''),
                'data' => new \external_value(PARAM_RAW, ''), // base64 with serialization before
            )
        );
    }

    private static function encode($data){
        return base64_encode(serialize($data));
    }
    private static function decode($data){
        return unserialize(base64_decode($data));
    }
}