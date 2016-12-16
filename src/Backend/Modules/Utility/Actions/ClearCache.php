<?php

namespace Backend\Modules\Utility\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language;
use Backend\Modules\Utility\Engine\Model as BackendCacheClearModel;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the index-action (default), it will display the setting-overview
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 * @author Davy Hellemans <davy.hellemans@netlash.com>
 */
class ClearCache extends BackendBaseActionEdit
{

    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->loadForm();
        $this->validateForm();
        $this->parse();
        $this->display();
    }

    /**
     * Load the form
     */
    private function loadForm()
    {
        // create form
        $this->frm = new BackendForm('settings');
    }

    /**
     * Parse the form
     */
    protected function parse()
    {

        // parse the form
        $this->tpl->assign('cleared', $this->get('fork.settings')->get($this->URL->getModule(), 'last_cleared', false));
        $this->frm->parse($this->tpl);
    }



    /**
     * Validates the form
     */
    private function validateForm()
    {
        // is the form submitted?
        if ($this->frm->isSubmitted()) {
            if ($this->frm->isCorrect()) {
                BackendCacheClearModel::clear();
                $this->get('fork.settings')->set($this->URL->getModule(), 'last_cleared', strtotime('now'));
                $this->redirect(BackendModel::createURLForAction('ClearCache') . '&report=success');
            }
        }
    }
}
