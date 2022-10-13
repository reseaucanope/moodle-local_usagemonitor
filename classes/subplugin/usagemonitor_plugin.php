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
 * Usagemonitor abstract parent class for subplugin 
 *
 * @package   local_usagemonitor
 * @copyright 2022 DNE - Ministere de l'Education Nationale
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_usagemonitor\subplugin;

defined('MOODLE_INTERNAL') || die();

abstract class usagemonitor_plugin {

    public abstract function get_subtype();

    public abstract function get_name();
    
    public function get_subplugins() {
        return array();
    }
}