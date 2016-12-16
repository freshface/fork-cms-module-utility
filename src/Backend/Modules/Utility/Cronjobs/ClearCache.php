<?php

namespace Backend\Modules\Utility\Cronjobs;

use Backend\Core\Engine\Base\Cronjob as BackendBaseCronjob;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Utility\Engine\Model as BackendCacheClearModel;

class ClearCache extends BackendBaseCronjob
{

    /*
        SITE_URL/backend/cronjob?module=Utility&action=ClearCache
    */
    
    /**
     * Execute the action
     */
    public function execute()
    {
        $response = @file_get_contents('php://input');

        if ((isset($response['source']) && $response['source'] == 'beanstalkapp')) {
            BackendCacheClearModel::clear();
            BackendModel::setModuleSetting('Utility', 'last_cleared', strtotime('now'));
        }

        parent::execute();
    }
}
