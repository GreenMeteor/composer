<?php

namespace humhub\modules\composer\services;

use Yii;
use humhub\modules\composer\helpers\IDEHelper;

/**
 * GruntService handles the execution of Grunt commands.
 */
class GruntService
{
    /**
     * Executes a Grunt command and returns the output.
     *
     * @param string $command The Grunt command to execute.
     * @param string|null $themeName The name of the theme to build (optional).
     * @return array The output of the Grunt command.
     * @throws \yii\web\ForbiddenHttpException If the user is not allowed to perform this action.
     * @throws \yii\web\ServerErrorHttpException If the working directory is invalid.
     */
    public function executeCommand(string $command, string $themeName = null): array
    {
        if (!Yii::$app->user->isAdmin()) {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
        }

        // Use IDEHelper to get the root directory (webroot)
        $workingDirectory = IDEHelper::getRootPath();

        if (!is_dir($workingDirectory)) {
            Yii::error('Invalid working directory: ' . $workingDirectory);
            throw new \yii\web\ServerErrorHttpException('Invalid working directory.');
        }

        Yii::info('Current Working Directory: ' . $workingDirectory);

        if ($themeName !== null) {
            $command .= ' --name=' . escapeshellarg($themeName);
        }

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

    /**
     * Builds assets using Grunt.
     *
     * @return array The output of the Grunt build-assets command.
     */
    public function buildAssets(): array
    {
        return $this->executeCommand('build-assets');
    }

    /**
     * Builds the search index using Grunt.
     *
     * @return array The output of the Grunt build-search command.
     */
    public function buildSearch(): array
    {
        return $this->executeCommand('build-search');
    }

    /**
     * Runs migrations using Grunt.
     *
     * @param string|null $module The name of the module to migrate.
     * @return array The output of the Grunt migrate-up command.
     */
    public function migrateUp(string $module = null): array
    {
        $command = 'migrate-up';
        if ($module !== null) {
            $command .= ' --module=' . escapeshellarg($module);
        }
        return $this->executeCommand($command);
    }
}
