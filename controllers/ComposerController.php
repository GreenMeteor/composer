<?php

namespace humhub\modules\composer\controllers;

use Yii;
use Symfony\Component\Process\Process;
use humhub\components\Controller;

class ComposerController extends Controller
{
    public function actionIndex()
    {
        $output = '';

        // Check if the form was submitted
        if (Yii::$app->request->isPost) {
            // Get the selected command option from the form
            $option = Yii::$app->request->post('option');

            // Define the Composer command based on the selected option
            switch ($option) {
                case 'self-update':
                    $command = ['/usr/local/bin/composer', 'self-update'];
                    break;
                case 'update':
                    $command = ['/usr/local/bin/composer', 'update'];
                    break;
                case 'install':
                    $command = ['/usr/local/bin/composer', 'install'];
                    break;
                default:
                    // Default to self-update if no option is selected
                    $command = ['/usr/local/bin/composer', 'update'];
            }

            // Create a new Process instance
            $process = new Process($command);

            // Run the command
            $process->run();

            // Check if the command was successful
            if ($process->isSuccessful()) {
                // Get the command output
                $output = $process->getOutput();
            } else {
                // Get the error output if the command failed
                $output = $process->getErrorOutput();
            }
        }

        return $this->render('index', ['output' => $output]);
    }
}
