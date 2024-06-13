<?php

namespace humhub\modules\composer\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use yii\web\BadRequestHttpException;

/**
 * GitController implements the actions for pulling from a Git repository.
 */
class GitController extends Controller
{
    /**
     * Handles the pull operation when the button is clicked.
     *
     * @return string The rendered view displaying the result of the pull operation.
     */
    public function actionPull(): string
    {
        // Check if the form is submitted
        if (Yii::$app->request->isPost) {
            // Verify CSRF token
            $this->validateCsrfToken();

            // Perform the pull operation
            $branch = Yii::$app->request->post('branch', 'master');
            $output = $this->pullFromRepository($branch);

            // Render the view with the pull result
            return $this->render('pull', ['output' => $output]);
        }

        // Render the initial pull form view
        return $this->render('pull');
    }

    private function validateCsrfToken(): void
    {
        $token = Yii::$app->request->post('_csrf');
        if (!Yii::$app->getRequest()->validateCsrfToken($token)) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }
    }

    /**
     * Pulls changes from the HumHub Git repository (master or develop branch) into the specified directory.
     *
     * @param string $branch The branch to pull from.
     * @return array Output messages from the pull operation.
     */
    private function pullFromRepository(string $branch = 'master'): array
    {
        // Define the URL of the HumHub repository
        $repositoryUrl = 'https://github.com/humhub/humhub';

        // Set the working directory where you want to pull the changes
        $workingDirectory = $_SERVER['DOCUMENT_ROOT'];

        // Log current working directory
        Yii::info('Current Working Directory: ' . $workingDirectory);

        // Attempt the pull operation with retries
        $maxRetries = 3;
        $retryCount = 0;
        $output = [];
        do {
            // Clean up previously generated folder contents
            $this->deleteDirectoryContents($workingDirectory . '/humhub');

            // Perform the pull operation
            $pullResult = $this->performPull($repositoryUrl, $workingDirectory, $branch, $output);

            if ($pullResult === true) {
                Yii::$app->session->setFlash('success', 'Git clone and file copy successful.');
                break;
            } else {
                Yii::$app->session->setFlash('error', 'Failed to clone from the HumHub Git repository. Retrying...');
                $retryCount++;
                usleep(500000);
            }
        } while ($retryCount < $maxRetries);

        if ($pullResult !== true) {
            Yii::$app->session->setFlash('error', 'Failed to clone from the HumHub Git repository after ' . $maxRetries . ' retries.');
        }

        return $output;
    }

    /**
     * Performs the Git clone operation.
     *
     * @param string $repositoryUrl The URL of the Git repository.
     * @param string $workingDirectory The working directory where to clone the repository.
     * @param string $branch The branch to pull from.
     * @param array $output Reference to the output array to store messages.
     * @return bool Whether the clone operation was successful.
     */
    private function performPull(string $repositoryUrl, string $workingDirectory, string $branch, array &$output): bool
    {
        // Check if it is a GitHub repository
        $curl = curl_init($repositoryUrl);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($status != 200) {
            Yii::$app->session->setFlash('error', 'Error: Not a GitHub repository.');
            return false;
        }

        // Execute GitHub clone command for the specified branch
        $clone = 'cd ' . $workingDirectory . ' && git clone -b ' . $branch . ' ' . $repositoryUrl . ' humhub 2>&1';
        exec($clone, $output, $returnCode);

        Yii::info('Clone Command executed: ' . $clone);
        Yii::info('Output: ' . print_r($output, true));
        Yii::info('Return code: ' . $returnCode);

        if ($returnCode === 0) {
            // Copy files and directories except for index.php, .htaccess, /protected/config, and /protected/vendor
            $ignore = [
                $workingDirectory . '/index.php',
                $workingDirectory . '/.htaccess',
                $workingDirectory . '/protected/config/common.php',
                $workingDirectory . '/protected/vendor/*'
            ];
            $this->copyDirectory($workingDirectory . '/humhub', $workingDirectory, $ignore);

            return true;
        } else {
            Yii::$app->session->setFlash('error', 'Failed to clone from the HumHub Git repository. Return code: ' . $returnCode);
            return false;
        }
    }

    /**
     * Copies files and directories recursively.
     *
     * @param string $source The source directory or file.
     * @param string $destination The destination directory.
     * @param array $ignore List of files or directories to ignore during copy.
     */
    private function copyDirectory(string $source, string $destination, array $ignore = []): void
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $files = glob($source . '/*');
        foreach ($files as $file) {
            $relativePath = str_replace($source, '', $file);
            if (!in_array($relativePath, $ignore)) {
                $dest = $destination . '/' . basename($file);
                if (is_dir($file)) {
                    $this->copyDirectory($file, $dest, $ignore);
                } else {
                    copy($file, $dest);
                }
            }
        }

        // Delete directory contents after copying is completed
        $this->deleteDirectoryContents($source);
    }

    /**
     * Deletes the contents of a directory and the directory itself if empty.
     *
     * @param string $dir The directory path.
     */
    private function deleteDirectoryContents(string $dir): void
    {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDirectoryContents($file);
                if (is_dir($file)) {
                    @rmdir($file);
                }
            } else {
                @unlink($file);
            }
        }

        // Check if the directory is completely empty
        if (count(glob($dir . '/*')) === 0) {
            // After deleting all files, remove the directory itself
            @rmdir($dir);
        }
    }
}
