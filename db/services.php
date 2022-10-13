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
 * usagemonitor external functions and service definitions.
 *
 * @package    local_usagemonitor
 * @copyright  2022 DNE - Ministere de l'Education Nationale
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$services = array(
    'local_usagemonitor_service' => array(
        'functions' => array ('local_usagemonitor_getdata'),
        'requiredcapability' => 'local/usagemonitor:webservice',
        'restrictedusers' => 0,
        'enabled' => 1,
        'shortname' =>  'local_usagemonitor_service',
        'downloadfiles' => 0,
        'uploadfiles'  => 0
    )
);

$functions = array(
    'local_usagemonitor_getdata' => array(
        'classname' => 'local_usagemonitor\external\get_data',
        'methodname' => 'get_data',
        'classpath' => 'local/usagemonitor/classes/external/get_data.php',
        'description' => 'Get data from a specific data subplugin',
        'type' => 'read',
        'capabilities' => 'local/usagemonitor:getdata',
    ),
);
