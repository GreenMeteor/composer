<?php

namespace humhub\modules\composer\controllers;

use Yii;
use humhub\modules\admin\components\Controller;

/**
 * GruntController implements the actions for managing Grunt tasks.
 */
class GruntController extends Controller
{
    /**
     * Displays the index page.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Builds assets using Grunt.
     *
     * @return string
     * @throws \yii\web\ForbiddenHttpException if the user is not allowed to perform this action.
     * @throws \yii\web\ServerErrorHttpException if the working directory is invalid.
     */
    public function actionBuildAssets()
    {
        $output = $this->executeGruntCommand('build-assets');
        return $this->renderPartial('build-assets', ['output' => $output]);
    }

    /**
     * Builds a theme using Grunt.
     *
     * @param string|null $themeName the name of the theme to build.
     * @return string
     * @throws \yii\web\ForbiddenHttpException if the user is not allowed to perform this action.
     * @throws \yii\web\ServerErrorHttpException if the working directory is invalid.
     */
    public function actionBuildTheme($themeName = null)
    {
        $gruntCommand = 'build-theme';
        if ($themeName !== null) {
            $gruntCommand .= ' --name=' . escapeshellarg($themeName);
        }
        $output = $this->executeGruntCommand($gruntCommand);
        return $this->renderPartial('build-theme', ['output' => $output]);
    }

    /**
     * Rebuilds the search index using Grunt.
     *
     * @return string
     * @throws \yii\web\ForbiddenHttpException if the user is not allowed to perform this action.
     * @throws \yii\web\ServerErrorHttpException if the working directory is invalid.
     */
    public function actionBuildSearch()
    {
        $output = $this->executeGruntCommand('build-search');
        return $this->renderPartial('build-search', ['output' => $output]);
    }

    /**
     * Runs migrations using Grunt.
     *
     * @param string|null $module the name of the module to migrate.
     * @return string
     * @throws \yii\web\ForbiddenHttpException if the user is not allowed to perform this action.
     * @throws \yii\web\ServerErrorHttpException if the working directory is invalid.
     */
    public function actionMigrateUp($module = null)
    {
        $gruntCommand = 'migrate-up';
        if ($module !== null) {
            $gruntCommand .= ' --module=' . escapeshellarg($module);
        }
        $output = $this->executeGruntCommand($gruntCommand);
        return $this->renderPartial('migrate-up', ['output' => $output]);
    }

    /**
     * Executes a Grunt command and returns the output.
     *
     * @param string $command the Grunt command to execute.
     * @return array the output of the Grunt command.
     * @throws \yii\web\ForbiddenHttpException if the user is not allowed to perform this action.
     * @throws \yii\web\ServerErrorHttpException if the working directory is invalid.
     */
    private function executeGruntCommand($command)
    {
        $output = [];

        // Check if the user has permission to run Grunt
        if (!Yii::$app->user->isAdmin()) {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
        }

        // Define the working directory where your Gruntfile is located
        $workingDirectory = $_SERVER['DOCUMENT_ROOT'];

        // Validate the working directory
        if (!is_dir($workingDirectory)) {
            Yii::error('Invalid working directory: ' . $workingDirectory);
            throw new \yii\web\ServerErrorHttpException('Invalid working directory.');
        }

        // Log current working directory
        Yii::info('Current Working Directory: ' . $workingDirectory);

        // Execute Grunt command using the Gruntfile.js from the root directory
        exec("cd $workingDirectory && grunt --gruntfile=$workingDirectory/Gruntfile.js $command 2>&1", $output, $returnCode);

        Yii::info('Grunt Command executed: grunt --gruntfile=' . $workingDirectory . '/Gruntfile.js ' . $command);
        Yii::info('Output: ' . print_r($output, true));
        Yii::info('Return code: ' . $returnCode);

        // Check if the command execution was successful
        if ($returnCode !== 0) {
            Yii::error('Failed to execute Grunt command: ' . $command . '. Return code: ' . $returnCode);
            Yii::$app->session->setFlash('error', 'Failed to execute Grunt command: ' . $command . '. Return code: ' . $returnCode);
        }

        // Return the output of the Grunt command
        return $output;
    }
}