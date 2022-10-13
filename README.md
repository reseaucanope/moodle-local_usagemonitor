# Local plugin : Usage Monitor for Moodle

- Source Code: 
- License: http://www.gnu.org/licenses/gpl-3.0.html

## Install from an archive

- Extract the archive in the /local/usagemonitor folder
- Install by connecting to your moodle as an administrator or user the CLI script **admin/cli/upgrade.php** if you have access to a console.

## Description

The usage monitor provides a basic framework to add your own custom data visualisations to your Moodle platforme. It works by using two types of subplugins that take care of managing the data and providing the visuals :
    - data subplugin : collect, store and provide data about your Moodle platforme. The data subplugins only need to implement the get_data function used to provides the data to the webservice. The usage monitor webservice can be called to fetch any *data subplugin* data.
    - stats subplugin : provide the visuals for the subplugin data. The stats subplugins need to implement the get_content function used to provide the visuals for the main page.

This plugin is intended to be used with at least one data or stats subplugins and does not provide any features by itself.

This plugin is developped and maintained by [Reseau-Canope](https://www.reseau-canope.fr/) since 2022.

## Documentation

### Installing a new data subplugin

Your new *data subplugin* must be placed in local/usagemonitor/data/*subpluginname*. It's mandatory to have a php file local/usagemonitor/data/*subpluginname*/classes/*usagemonitordata_subpluginname.php*.

Its content will look like this :

```php
namespace usagemonitordata_mysubplugin;

defined('MOODLE_INTERNAL') || die();

use local_usagemonitor\subplugin\usagemonitordata_plugin;


class usagemonitordata_mysubplugin extends usagemonitordata_plugin {

    public function get_name() {
        return get_string('pluginname', 'usagemonitordata_mysubplugin');
    }

    public static function get_data($params = array()) {
        ...
        return ...;
    }
    ...

}
```
    

### Installing a new stats subplugin

Your new *stats subplugin* must be placed in local/usagemonitor/stats/*subpluginname*. It's mandatory to have a php file local/usagemonitor/stats/*subpluginname*/classes/*usagemonitorstats_subpluginname.php*.

Its content will look like this :

```php
namespace usagemonitorstats_mysubplugin;

defined('MOODLE_INTERNAL') || die();

use usagemonitordata_mysubplugin\usagemonitordata_mydatasubplugin; // if you're using a data subplugin 
use local_usagemonitor\subplugin\usagemonitorstats_plugin;

class usagemonitorstats_mysubplugin extends usagemonitorstats_plugin {

    public function get_name() {
        return get_string('pluginname', 'usagemonitorstats_mysubplugin');
    }

    public function get_content() {
        ...
        return ...;
    }
    ...

}
```

It's possible to add an entry in the admin menu, under the usagemonitor section, by putting the following lines in the *settings.php* file of your subplugin : 


```php
defined('MOODLE_INTERNAL') || die;

$ADMIN->add('local_usagemonitor', new admin_externalpage('usagemonitorstats_mysubplugin', new lang_string('pluginname', 'usagemonitorstats_mysubplugin'),
            $CFG->wwwroot.'/local/usagemonitor/index.php?view=mysubplugin', 'usagemonitorstats/mysubplugin:view'));
```

## Requirements

- Moodle (Version 3.9 or later) 
- PHP 7.2 or later.
