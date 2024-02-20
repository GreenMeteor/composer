<?php

namespace humhub\modules\composer\controllers;

use Yii;
use yii\helpers\Url;
use Composer\Factory;
use Composer\IO\BufferIO;
use Symfony\Component\Console\Input\ArrayInput;
use humhub\modules\admin\components\Controller;
use Symfony\Component\Console\Output\BufferedOutput;

require_once(Yii::getAlias('@composer') . '/vendor' . '/autoload.php');

class ComposerController extends Controller
{
    public function actionIndex()
    {
        // Set the HOME environment variable to the user's home directory
        putenv('HOME=' . $_SERVER['DOCUMENT_ROOT']);

        // Assume $someCondition and $anotherCondition are defined elsewhere
        $someCondition = true;
        $anotherCondition = false;

        // Determine the command based on some condition or input
        $command = 'self-update';

        // Condition to determine the command
        if ($someCondition) {
            $command = 'install';
        } elseif ($anotherCondition) {
            $command = 'update';
        }

        // Prepare input with the determined command
        $inputOptions = ['command' => $command];

        // Create an instance of BufferIO
        $io = new BufferIO();

        // Load Composer application
        $composer = Factory::create($io, Url::to('composer.json'));

        // Create Composer Application instance
        $application = new \Composer\Console\Application();
        $application->setAutoExit(false);

        // Prepare input and output
        $input = new ArrayInput($inputOptions);
        $output = new BufferedOutput();

        // Run the Composer command
        $application->run($input, $output);

        // Get the output of the command
        $outputText = $output->fetch();

        // Clear Composer cache
        $composerCacheDir = getenv('COMPOSER_HOME') ?: (getenv('HOME') . DIRECTORY_SEPARATOR . '.composer');
        $cacheFiles = glob($composerCacheDir . '/cache/*');
        foreach ($cacheFiles as $cacheFile) {
            if (is_file($cacheFile)) {
                unlink($cacheFile);
            }
        }

        return $this->render('index', ['output' => $outputText]);
    }
}
