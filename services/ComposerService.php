<?php

namespace humhub\modules\composer\services;

use Yii;
use Composer\Factory;
use Composer\IO\BufferIO;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ComposerService
{
    /**
     * Runs a specified Composer command.
     *
     * @param string $command The Composer command to run (e.g., 'install', 'update', 'self-update')
     * @return string Output of the Composer command
     */
    public function runComposerCommand(string $command): string
    {
        // Ensure Composer autoload is included
        require_once(Yii::getAlias('@composer') . '/vendor/autoload.php');

        // Set the HOME environment variable to the document root
        putenv('HOME=' . $_SERVER['DOCUMENT_ROOT']);

        // Prepare input options for the command
        $inputOptions = ['command' => $command];

        // Create an instance of BufferIO and load Composer application
        $io = new BufferIO();
        $composer = Factory::create($io, Yii::getAlias('@composer') . '/composer.json');
        $application = new \Composer\Console\Application();
        $application->setAutoExit(false);

        // Prepare input and output objects for the Composer application
        $input = new ArrayInput($inputOptions);
        $output = new BufferedOutput();

        // Run the Composer command
        $application->run($input, $output);

        // Clear Composer cache after running the command
        $this->clearComposerCache();

        // Return the command output
        return $output->fetch();
    }

    /**
     * Clears the Composer cache.
     */
    private function clearComposerCache(): void
    {
        $composerCacheDir = getenv('COMPOSER_HOME') ?: (getenv('HOME') . DIRECTORY_SEPARATOR . '.composer');
        $cacheFiles = glob($composerCacheDir . '/cache/*');
        
        foreach ($cacheFiles as $cacheFile) {
            if (is_file($cacheFile)) {
                unlink($cacheFile);
            }
        }
    }
}
