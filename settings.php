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
 * Settings for local_usagemonitor
 * 
 * @package   local_usagemonitor
 * @copyright 2022 DNE - Ministere de l'Education Nationale
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;


$ADMIN->add('root', new admin_category('local_usagemonitor', new lang_string('pluginname', 'local_usagemonitor')), 'users');

foreach (core_plugin_manager::instance()->get_plugins_of_type('usagemonitordata') as $plugin) {
    $plugin->load_settings($ADMIN, 'local_usagemonitor', $hassiteconfig);
}

foreach (core_plugin_manager::instance()->get_plugins_of_type('usagemonitorstats') as $plugin) {
    $plugin->load_settings($ADMIN, 'local_usagemonitor', $hassiteconfig);
}
