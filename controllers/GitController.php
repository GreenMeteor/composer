<?php

namespace humhub\modules\composer\controllers;

use Yii;
use yii\helpers\Url;
use Gitonomy\Git\Repository;
use humhub\modules\admin\components\Controller;

/**
 * GitController implements the actions for pulling from a Git repository.
 */
class GitController extends Controller
{
    /**
     * Pulls changes from the HumHub Git repository (master branch).
     *
     * This action pulls changes from the HumHub Git repository's master branch
     * into the root directory where HumHub is installed.
     *
     * @return string The rendered view displaying the result of the pull operation.
     */
    public function actionPull()
    {
        // Define repository details
        $repositoryUrl = 'https://github.com/humhub/humhub.git';
        $branch = 'master';

        // Set the working directory to the root directory where HumHub is installed
        $workingDirectory = Yii::getAlias('@app');

        // Initialize Git repository
        $repository = new Repository($workingDirectory);

        // Execute Git pull command
        $output = [];
        try {
            $repository->run('pull', ['origin', $branch]);
            $message = 'Git pull successful.';
        } catch (\Exception $e) {
            $message = 'Failed to pull from the HumHub Git repository: ' . $e->getMessage();
        }

        return $this->render('pull', ['message' => $message]);
    }
}
