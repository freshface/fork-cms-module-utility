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
class Favicons extends BackendBaseActionEdit
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

        $this->resolutions = array(
            array('name' =>'apple-touch-icon', 'seperator' => '-', 'resolution' => '57x57'),
            array('name' =>'apple-touch-icon', 'seperator' => '-', 'resolution' => '60x60'),
            array('name' =>'apple-touch-icon', 'seperator' => '-', 'resolution' => '72x72'),
            array('name' =>'apple-touch-icon', 'seperator' => '-', 'resolution' => '76x76'),
            array('name' =>'apple-touch-icon', 'seperator' => '-', 'resolution' => '114x114'),
            array('name' =>'apple-touch-icon', 'seperator' => '-', 'resolution' => '120x120'),
            array('name' =>'apple-touch-icon', 'seperator' => '-', 'resolution' => '144x144'),
            array('name' =>'apple-touch-icon', 'seperator' => '-', 'resolution' => '152x152'),
            array('name' =>'favicon', 'seperator' => '-', 'resolution' => '16x16'),
            array('name' =>'favicon', 'seperator' => '-', 'resolution' => '32x32'),
            array('name' =>'favicon', 'seperator' => '-', 'resolution' => '96x96'),
            array('name' =>'favicon', 'seperator' => '-', 'resolution' => '128x128'),
            array('name' =>'favicon', 'seperator' => '-', 'resolution' => '196x196'),
            array('name' =>'mstile', 'seperator' => '-', 'resolution' => '70x70'),
            array('name' =>'mstile', 'seperator' => '-', 'resolution' => '144x144'),
            array('name' =>'mstile', 'seperator' => '-', 'resolution' => '150x150'),
            array('name' =>'mstile', 'seperator' => '-', 'resolution' => '310x150'),
            array('name' =>'mstile', 'seperator' => '-', 'resolution' => '310x310'),
        );

        $this->frm->addImage('all');
        $this->frm->addImage('og');

        foreach ($this->resolutions as &$resolution) {
            $field = $this->frm->addImage('resolution_' . $resolution['name'] . '_' . $resolution['resolution']);
            $resolution['field'] = $field->parse();
        }
    }

    /**
     * Parse the form
     */
    protected function parse()
    {
        // parse the form
        $this->frm->parse($this->tpl);
        $this->tpl->assign('resolutions', $this->resolutions);
    }



    /**
     * Validates the form
     */
    private function validateForm()
    {
        // is the form submitted?
        if ($this->frm->isSubmitted()) {
            if ($this->frm->isCorrect()) {

                // the image path
                $imagePath = FRONTEND_PATH . '/Themes/' . $this->get('fork.settings')->get('Core', 'theme', 'core') . '/Icons';

                // image provided?
                if ($this->frm->getField('og')->isFilled()) {
                    $this->frm->getField('og')->createThumbnail(
                        $imagePath . '/og.png',
                        1200,
                        630,
                        true,
                        false,
                        100
                    );
                }

                if ($this->frm->getField('all')->isFilled()) {
                    foreach ($this->resolutions as &$resolution) {
                        $dimensions = explode('x', $resolution['resolution']);

                        $width = $dimensions[0];
                        $height = $dimensions[1];

                        $this->frm->getField('all')->createThumbnail(
                            $imagePath . '/' . $resolution['name'] . $resolution['seperator'] . $resolution['resolution'] . '.png',
                            $width,
                            $height,
                            true,
                            false,
                            100
                        );
                    }
                }


                // replace
                foreach ($this->resolutions as &$resolution) {
                    $field = 'resolution_' . $resolution['name'] . '_' . $resolution['resolution'];

                    if ($this->frm->getField($field)->isFilled()) {
                        $dimensions = explode('x', $resolution['resolution']);

                        $width = $dimensions[0];
                        $height = $dimensions[1];

                        $this->frm->getField($field)->createThumbnail(
                            $imagePath . '/' . $resolution['name'] . $resolution['seperator'] . $resolution['resolution'] . '.png',
                            $width,
                            $height,
                            true,
                            false,
                            100
                        );
                    }
                }

                $this->redirect(BackendModel::createURLForAction('Favicons') . '&report=success');
            }
        }
    }
}
