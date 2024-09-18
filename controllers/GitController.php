<?php

namespace humhub\modules\composer\controllers;


use humhub\modules\admin\components\Controller;
use humhub\modules\composer\services\GitService;

/**
 * Controller to handle Git-related actions.
 */
class GitController extends Controller
{
    /**
     * Action to perform a Git pull from the repository.
     *
     * @return string
     */
    public function actionPull()
    {
        if (\Yii::$app->request->isPost) {
            $branch = \Yii::$app->request->post('branch', 'master');
            $gitService = new GitService();
            $output = $gitService->pullFromRepository('https://github.com/humhub/humhub', $_SERVER['DOCUMENT_ROOT'], $branch);

            return $this->render('pull', ['output' => $output]);
        }
        return $this->render('pull');
    }
}
