<?php

namespace humhub\modules\composer\services;

use Yii;

class GitService
{
    /**
     * Executes a Git pull or clone from the specified branch.
     *
     * @param string $repositoryUrl Git repository URL
     * @param string $workingDirectory Directory where the repository will be cloned
     * @param string $branch Branch to pull from (default: 'master')
     * @param int $maxRetries Maximum number of retry attempts for Git clone (default: 3)
     * @return array Output of the Git pull operation
     */
    public function pullFromRepository(string $repositoryUrl, string $workingDirectory, string $branch = 'master', int $maxRetries = 3): array
    {
        $output = [];
        $retryCount = 0;
        $success = false;

        do {
            $this->deleteDirectoryContents($workingDirectory . '/humhub');
            $success = $this->performGitClone($repositoryUrl, $workingDirectory, $branch, $output);

            if ($success) {
                Yii::$app->session->setFlash('success', 'Git clone and file copy successful.');
                break;
            } else {
                Yii::$app->session->setFlash('error', 'Failed to clone from the Git repository. Retrying...');
                $retryCount++;
                usleep(500000); // Delay between retries
            }
        } while ($retryCount < $maxRetries);

        if (!$success) {
            Yii::$app->session->setFlash('error', 'Failed to clone from the Git repository after ' . $maxRetries . ' retries.');
        }

        return $output;
    }

    /**
     * Performs a Git clone operation.
     *
     * @param string $repositoryUrl Git repository URL
     * @param string $workingDirectory Directory where the repository will be cloned
     * @param string $branch Branch to pull from (default: 'master')
     * @param array &$output Output from the clone command
     * @return bool Success or failure of the Git clone operation
     */
    private function performGitClone(string $repositoryUrl, string $workingDirectory, string $branch, array &$output): bool
    {
        // Validate the repository URL
        if (!$this->isValidGitRepository($repositoryUrl)) {
            Yii::$app->session->setFlash('error', 'Error: Not a valid GitHub repository.');
            return false;
        }

        // Execute the Git clone command
        $cloneCommand = 'cd ' . escapeshellarg($workingDirectory) . ' && git clone -b ' . escapeshellarg($branch) . ' ' . escapeshellarg($repositoryUrl) . ' humhub 2>&1';
        exec($cloneCommand, $output, $returnCode);

        if ($returnCode === 0) {
            // Copy the necessary files from the cloned repository
            $ignore = ['/index.php', '/.htaccess', '/protected/config/common.php', '/protected/vendor'];
            $this->copyDirectory($workingDirectory . '/humhub', $workingDirectory, $ignore);
            return true;
        } else {
            Yii::$app->session->setFlash('error', 'Git clone failed with return code: ' . $returnCode);
            return false;
        }
    }

    /**
     * Validates if the repository URL is a valid GitHub repository.
     *
     * @param string $repositoryUrl GitHub repository URL
     * @return bool Whether the repository is valid
     */
    private function isValidGitRepository(string $repositoryUrl): bool
    {
        $curl = curl_init($repositoryUrl);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $statusCode === 200;
    }

    /**
     * Copies files from one directory to another, excluding specified files.
     * Ensures directories and files are given 0755 permissions.
     *
     * @param string $source Source directory
     * @param string $destination Destination directory
     * @param array $ignore List of files to ignore
     */
    private function copyDirectory(string $source, string $destination, array $ignore = []): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relativePath = substr($item->getPathname(), strlen($source));

            if ($this->shouldIgnore($relativePath, $ignore)) {
                continue;
            }

            $target = $destination . $relativePath;
            if ($item->isDir()) {
                if (!is_dir($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                copy($item->getPathname(), $target);
                
                // Set permissions to 0755 for both files and directories
                chmod($target, 0755);
            }
        }

        $this->deleteDirectoryContents($source);
    }

    /**
     * Determines if a path should be ignored based on the ignore list.
     *
     * @param string $path File path
     * @param array $ignore List of files to ignore
     * @return bool Whether the path should be ignored
     */
    private function shouldIgnore(string $path, array $ignore): bool
    {
        foreach ($ignore as $pattern) {
            if (fnmatch($pattern, $path)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Deletes the contents of a directory.
     *
     * @param string $dir Directory path
     */
    private function deleteDirectoryContents(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }
    }
}
