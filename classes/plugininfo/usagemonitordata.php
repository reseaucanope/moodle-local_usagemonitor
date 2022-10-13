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
 * usagemonitordata subplugin info class.
 *
 * @package   local_usagemonitor
 * @copyright 2022 DNE - Ministere de l'Education Nationale
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_usagemonitor\plugininfo;

use core\plugininfo\base, core_plugin_manager, moodle_url;

defined('MOODLE_INTERNAL') || die();


class usagemonitordata extends base {
    /**
     * Finds all enabled plugins, the result may include missing plugins.
     * @return array|null of enabled plugins $pluginname=>$pluginname, null means unknown
     */
    public static function get_enabled_plugins() {
        global $DB;


        $plugins = core_plugin_manager::instance()->get_installed_plugins('usagemonitordata');
        if (!$plugins) {
            return array();
        }
        $installed = array();
        foreach ($plugins as $plugin => $version) {
            $installed[] = 'usagemonitordata_'.$plugin;
        }


        list($installed, $params) = $DB->get_in_or_equal($installed, SQL_PARAMS_NAMED);
        $disabled = $DB->get_records_select('config_plugins', "plugin $installed AND name = 'disabled'", $params, 'plugin ASC');
        foreach ($disabled as $conf) {
            if (empty($conf->value)) {
                continue;
            }
            list($type, $name) = explode('_', $conf->plugin, 2);
            unset($plugins[$name]);
        }

        $enabled = array();
        foreach ($plugins as $plugin => $version) {
            $enabled[$plugin] = $plugin;
        }

        return $enabled;
    }

    public function is_uninstall_allowed() {
        return true;
    }

    /**
     * Return URL used for management of plugins of this type.
     * @return moodle_url
     */
    public static function get_manage_url() {
        return '';
    }

    /**
     * Pre-uninstall hook.
     * @private
     */
    public function uninstall_cleanup() {
        global $DB;

        parent::uninstall_cleanup();
    }

    public function get_settings_section_name() {
        return $this->type . '_' . $this->name;
    }

    /**
     * Loads plugin settings to the settings tree
     *
     * This function usually includes settings.php file in plugins folder.
     * Alternatively it can create a link to some settings page (instance of admin_externalpage)
     *
     * @param \part_of_admin_tree $adminroot
     * @param string $parentnodename
     * @param bool $hassiteconfig whether the current user has moodle/site:config capability
     */
    public function load_settings(\part_of_admin_tree $adminroot, $parentnodename, $hassiteconfig) {
        global $CFG, $USER, $DB, $OUTPUT, $PAGE; // In case settings.php wants to refer to them.
        $ADMIN = $adminroot; // May be used in settings.php.
        $plugininfo = $this; // Also can be used inside settings.php.

        if (!$this->is_installed_and_upgraded()) {
            return;
        }

        if (!file_exists($this->full_path('settings.php'))) {
            return;
        }

        include($this->full_path('settings.php'));
    }

    public static function get_data($pluginname, $extradata = null) {
        if (!is_null($extradata) && !is_array($extradata)) {
            throw new \Exception('If set, extradata must be an array');
        }

        $dataSubplugins = self::get_enabled_plugins();
        if (!isset($dataSubplugins[$pluginname])) {
            throw new \Exception('Subplugin '.$pluginname.' does not exist');
        }

        $classname = '\usagemonitordata_'.$pluginname.'\usagemonitordata_'.$pluginname;
        return $classname::get_data($extradata);
    }
}
