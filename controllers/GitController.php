<?php

namespace humhub\modules\composer\controllers;

use Yii;
use humhub\modules\admin\components\Controller;

/**
 * GitController implements the actions for pulling from a Git repository.
 */
class GitController extends Controller
{
    /**
     * Pulls changes from the HumHub Git repository (master branch) into the specified directory.
     *
     * @return string The rendered view displaying the result of the pull operation.
     */
    public function actionPull()
    {
        // Define the URL of the HumHub repository
        $repositoryUrl = 'https://github.com/humhub/humhub';

        // Set the working directory where you want to pull the changes
        $workingDirectory = $_SERVER['DOCUMENT_ROOT'];

        // Log current working directory
        Yii::info('Current Working Directory: ' . $workingDirectory);

        // Check if it is a GitHub repository
        $curl = curl_init($repositoryUrl);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($status != 200) {
            Yii::$app->session->setFlash('error', 'Error: Not a GitHub repository.');
        } else {
            // Execute GitHub clone command
            $output = [];
            $clone = 'cd ' . $workingDirectory . ' && git clone ' . $repositoryUrl . ' humhub 2>&1';
            exec($clone, $output, $returnCode);

            Yii::info('Clone Command executed: ' . $clone);
            Yii::info('Output: ' . print_r($output, true));
            Yii::info('Return code: ' . $returnCode);

            if ($returnCode === 0) {
                // Copy files and directories except for index.php, .htaccess, /protected/config, and /protected/vendor
                $ignore = ['index.php', '.htaccess', '/protected/config', '/protected/vendor'];
                $files = glob($workingDirectory . '/humhub/*');
                foreach ($files as $file) {
                    $fileName = basename($file);
                    if (!in_array($fileName, $ignore)) {
                        if (is_dir($file)) {
                            $targetDir = $workingDirectory . '/' . $fileName;
                            if (!file_exists($targetDir)) {
                                mkdir($targetDir, 0755, true);
                            }
                            $this->copyDirectory($file, $targetDir);
                        } else {
                            copy($file, $workingDirectory . '/' . $fileName);
                        }
                    }
                }

                // Delete all files within the /humhub directory
                $this->deleteDirectoryContents($workingDirectory . '/humhub');

                // Unlink the /humhub directory
                $humhubDir = $workingDirectory . '/humhub';
                if (is_dir($humhubDir)) {
                    $this->deleteDirectory($humhubDir);
                }

                Yii::$app->session->setFlash('success', 'Git clone and file copy successful.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to clone from the HumHub Git repository. Return code: ' . $returnCode);
            }
        }

        return $this->render('pull', ['output' => $output]);
    }

    /**
     * Recursively copy a directory and its contents.
     */
    private function copyDirectory($source, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $files = glob($source . '/*');
        foreach ($files as $file) {
            $dest = $destination . '/' . basename($file);
            if (is_dir($file)) {
                $this->copyDirectory($file, $dest);
            } else {
                copy($file, $dest);
            }
        }
    }

    /**
     * Recursively delete all files within a directory.
     */
    private function deleteDirectoryContents($dir)
    {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDirectoryContents($file);
            } else {
                unlink($file);
            }
        }
    }

    /**
     * Recursively delete a directory and its contents.
     */
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
}
