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
 * Index page for the plugin local_usagemonitor
 * 
 * @package   local_usagemonitor
 * @copyright 2022 DNE - Ministere de l'Education Nationale
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

$context = context_system::instance();

require_login();
$PAGE->set_context($context);
$PAGE->set_url('/local/usagemonitor/index.php');

$PAGE->set_title(get_string('pluginname', 'local_usagemonitor'));
$PAGE->set_heading('');

require_capability('local/usagemonitor:view', $context);

echo $OUTPUT->header();

$plugins = local_usagemonitor\plugininfo\usagemonitorstats::get_enabled_plugins();

$plugin = optional_param('view', '', PARAM_ALPHA);

$html = '';
if ($plugin && !in_array($plugin, $plugins)) {
    $a = new stdclass();
    $a->plugin = $plugin;
    print_error('missing_plugin', 'local_usagemonitor', '', $a);
} elseif ($plugin) {
    require_capability('usagemonitorstats/'.$plugin.':view', $context);
    $html.= get_plugin_content($plugin);
} else {
    foreach($plugins as $plugin) {
        $capa = 'usagemonitorstats/'.$plugin.':view';
        if (has_capability($capa, $context)) {
            $html.= get_plugin_content($plugin);
            $html.='<br>';
        }
    }
}

function get_plugin_content($plugin) {
    global $CFG;
    
    $classPath = $CFG->dirroot.'/local/usagemonitor/stats/'.$plugin.'/classes/usagemonitorstats_'.$plugin.'.php';
    if (file_exists($classPath)) {
        require_once($classPath);
        $classname = 'usagemonitorstats_'.$plugin;
        $classname .= '\\'.$classname; // take care of namespace
        $statspluginInstance = new $classname;
        return $statspluginInstance->get_content();
    }
    return '';
}

$css_url = new moodle_url('/lib/jquery/ui-1.12.1/jquery-ui.min.css');
echo '<link rel="stylesheet" href="'.$css_url->out().'"><p>'.$html.'</p>';

echo $OUTPUT->footer();
