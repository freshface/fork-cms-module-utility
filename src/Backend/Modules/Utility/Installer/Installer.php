<?php

namespace Backend\Modules\Utility\Installer;

use Backend\Core\Installer\ModuleInstaller;
use Backend\Core\Engine\Model as BackendModel;


/**
 * Installer for the Cache Clear module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Installer extends ModuleInstaller
{
    public function install()
    {
        // import the sql
        //$this->importSQL(dirname(__FILE__) . '/Data/install.sql');

        // install the module in the database
        $this->addModule('Utility');

        // install the locale, this is set here beceause we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setActionRights(1, 'Utility', 'ClearCache');
        $this->setActionRights(1, 'Utility', 'Favicons');

        // settings navigation
        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModuleId = $this->setNavigation($navigationSettingsId, 'Utility', 'utility/clear_cache');
        $this->setNavigation($navigationModuleId, 'ClearCache', 'utility/clear_cache');
        $this->setNavigation($navigationModuleId, 'Favicons', 'utility/favicons');

        $this->setSetting('Core', 'google_maps_key', '');

    }
}
