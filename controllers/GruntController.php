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
     * @return string the rendering result.
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Builds assets using Grunt.
     *
     * @return string the rendering result.
     * @throws \yii\web\ForbiddenHttpException if the user is not allowed to perform this action.
     * @throws \yii\web\ServerErrorHttpException if the working directory is invalid.
     */
    public function actionBuildAssets()
    {
        try {
            $output = $this->executeGruntCommand('build-assets');
            return $this->renderPartial('build-assets', ['output' => $output]);
        } catch (\Exception $e) {
            Yii::error('Error executing Grunt command: ' . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Error executing Grunt command: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Builds a theme using Grunt.
     *
     * @param string|null $themeName the name of the theme to build.
     * @return string the rendering result.
     * @throws \yii\web\ForbiddenHttpException if the user is not allowed to perform this action.
     * @throws \yii\web\ServerErrorHttpException if the working directory is invalid.
     */
    public function actionBuildTheme($themeName = null)
    {
        try {
            $gruntCommand = 'build-theme';
            if ($themeName !== null) {
                $gruntCommand .= ' --name=' . escapeshellarg($themeName);
            }
            $output = $this->executeGruntCommand($gruntCommand);
            return $this->renderPartial('build-theme', ['output' => $output]);
        } catch (\Exception $e) {
            Yii::error('Error executing Grunt command: ' . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Error executing Grunt command: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Builds the search index using Grunt.
     *
     * @return string the rendering result.
     * @throws \yii\web\ForbiddenHttpException if the user is not allowed to perform this action.
     * @throws \yii\web\ServerErrorHttpException if the working directory is invalid.
     */
    public function actionBuildSearch()
    {
        try {
            $output = $this->executeGruntCommand('build-search');
            return $this->renderPartial('build-search', ['output' => $output]);
        } catch (\Exception $e) {
            Yii::error('Error executing Grunt command: ' . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Error executing Grunt command: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Runs migrations using Grunt.
     *
     * @param string|null $module the name of the module to migrate.
     * @return string the rendering result.
     * @throws \yii\web\ForbiddenHttpException if the user is not allowed to perform this action.
     * @throws \yii\web\ServerErrorHttpException if the working directory is invalid.
     */
    public function actionMigrateUp($module = null)
    {
        try {
            $gruntCommand = 'migrate-up';
            if ($module !== null) {
                $gruntCommand .= ' --module=' . escapeshellarg($module);
            }
            $output = $this->executeGruntCommand($gruntCommand);
            return $this->renderPartial('migrate-up', ['output' => $output]);
        } catch (\Exception $e) {
            Yii::error('Error executing Grunt command: ' . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Error executing Grunt command: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
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

        if (!Yii::$app->user->isAdmin()) {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
        }

        $workingDirectory = $_SERVER['DOCUMENT_ROOT'];

        if (!is_dir($workingDirectory)) {
            Yii::error('Invalid working directory: ' . $workingDirectory);
            throw new \yii\web\ServerErrorHttpException('Invalid working directory.');
        }

        Yii::info('Current Working Directory: ' . $workingDirectory);

        exec("cd $workingDirectory && grunt --gruntfile=$workingDirectory/Gruntfile.js $command 2>&1", $output, $returnCode);

        Yii::info('Grunt Command executed: grunt --gruntfile=' . $workingDirectory . '/Gruntfile.js ' . $command);
        Yii::info('Output: ' . print_r($output, true));
        Yii::info('Return code: ' . $returnCode);

        if ($returnCode !== 0) {
            Yii::error('Failed to execute Grunt command: ' . $command . '. Return code: ' . $returnCode);
            Yii::$app->session->setFlash('error', 'Failed to execute Grunt command: ' . $command . '. Return code: ' . $returnCode);
        }

        return $output;
    }
}
