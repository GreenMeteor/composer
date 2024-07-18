<?php

namespace humhub\modules\composer\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use yii\web\BadRequestHttpException;

class GitController extends Controller
{
    public function actionPull(): string
    {
        if (Yii::$app->request->isPost) {
            $this->validateCsrfToken();
            $branch = Yii::$app->request->post('branch', 'master');
            $output = $this->pullFromRepository($branch);
            return $this->render('pull', ['output' => $output]);
        }
        return $this->render('pull');
    }

    private function validateCsrfToken(): void
    {
        $token = Yii::$app->request->post('_csrf');
        if (!Yii::$app->getRequest()->validateCsrfToken($token)) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }
    }

    private function pullFromRepository(string $branch = 'master'): array
    {
        $repositoryUrl = 'https://github.com/humhub/humhub';
        $workingDirectory = $_SERVER['DOCUMENT_ROOT'];
        Yii::info('Current Working Directory: ' . $workingDirectory);

        $maxRetries = 3;
        $retryCount = 0;
        $output = [];
        do {
            $this->deleteDirectoryContents($workingDirectory . '/humhub');
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

    private function performPull(string $repositoryUrl, string $workingDirectory, string $branch, array &$output): bool
    {
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

        $clone = 'cd ' . $workingDirectory . ' && git clone -b ' . $branch . ' ' . $repositoryUrl . ' humhub 2>&1';
        exec($clone, $output, $returnCode);

        Yii::info('Clone Command executed: ' . $clone);
        Yii::info('Output: ' . print_r($output, true));
        Yii::info('Return code: ' . $returnCode);

        if ($returnCode === 0) {
            $ignore = [
                '/index.php',
                '/.htaccess',
                '/protected/config/common.php',
                '/protected/vendor'
            ];
            $this->copyDirectory($workingDirectory . '/humhub', $workingDirectory, $ignore);
            return true;
        } else {
            Yii::$app->session->setFlash('error', 'Failed to clone from the HumHub Git repository. Return code: ' . $returnCode);
            return false;
        }
    }

    private function copyDirectory(string $source, string $destination, array $ignore = []): void
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relPath = substr($item->getPathname(), strlen($source));
            if ($this->shouldIgnore($relPath, $ignore)) {
                continue;
            }

            $target = $destination . $relPath;

            if ($item->isDir()) {
                if (!is_dir($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                copy($item->getPathname(), $target);
                chmod($target, 0644);
            }
        }

        $this->deleteDirectoryContents($source);
    }

    private function shouldIgnore(string $path, array $ignore): bool
    {
        foreach ($ignore as $pattern) {
            if (fnmatch($pattern, $path)) {
                return true;
            }
        }
        return false;
    }

    private function deleteDirectoryContents(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }

        if (is_dir($dir)) {
            rmdir($dir);
        }
    }
}
