<?php

namespace Backend\Modules\Utility\Engine;

use Backend\Core\Engine\Model as BackendModel;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;

/**
 * In this file we store all generic functions that we will be using in the Facebook Feed module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Model
{
    public static function clear()
    {
        $finder = new Finder();
        $fs = new Filesystem();

        $pathFrontend = FRONTEND_PATH . '/Cache';
        $pathBackend = BACKEND_PATH . '/Cache';

        $folders = array(
                        $pathFrontend . '/CompiledTemplates',
                        $pathFrontend . '/CachedTemplates',
                        $pathFrontend . '/Locale',
                        $pathFrontend . '/MinifiedCSS',
                        $pathFrontend . '/MinifiedJs',
                        $pathFrontend . '/Search',
                        $pathBackend . '/CompiledTemplates',
                        $pathBackend . '/Locale',
                        $pathBackend . '/MinifiedCSS',
                        $pathBackend . '/MinifiedJs',
                        //PATH_WWW . '/app/cache'
                    );

        foreach ($folders  as $folder) {
            if ($fs->exists($folder)) {
                $content = $finder->in($folder);
                foreach ($content as $file) {
                    $fs->remove($file->getRealpath());
                }
            }
        }
    }
}
