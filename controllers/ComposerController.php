<?php

namespace humhub\modules\composer\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\composer\services\ComposerService;

/**
 * Controller to handle Composer-related actions.
 */
class ComposerController extends Controller
{
    /**
     * Default action to run a Composer command.
     *
     * @return string
     */
    public function actionIndex()
    {
        $composerService = new ComposerService();
        $command = 'install';
        $output = $composerService->runComposerCommand($command);

        return $this->render('index', ['output' => $output]);
    }
}
