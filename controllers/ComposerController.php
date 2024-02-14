<?php

namespace humhub\modules\composer\controllers;

use humhub\components\Controller;

class ComposerController extends Controller
{
    public function actionIndex()
    {
        $output = shell_exec('composer update');
        return $this->render('index', ['output' => $output]);
    }
}