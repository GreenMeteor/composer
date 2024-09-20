<?php

namespace humhub\modules\composer\controllers;

use Yii;
use humhub\modules\admin\components\Controller;
use humhub\modules\composer\services\GruntService;

/**
 * GruntController implements the actions for managing Grunt tasks.
 */
class GruntController extends Controller
{
    private $gruntService;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->gruntService = new GruntService();
    }

    /**
     * Displays the index page.
     *
     * @return string The rendering result.
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Builds assets using Grunt.
     *
     * @return string The rendering result.
     */
    public function actionBuildAssets()
    {
        try {
            $output = $this->gruntService->buildAssets();
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
     * @param string|null $themeName The name of the theme to build.
     * @return string The rendering result.
     */
    public function actionBuildTheme()
    {
        $themeName = Yii::$app->request->post('themeName');

        if ($themeName === null) {
            throw new \yii\web\BadRequestHttpException('Theme name cannot be null.');
        }

        $gruntService = new GruntService();
        $output = $gruntService->buildTheme($themeName);

        return $this->asJson(['success' => true, 'output' => $output]);
    }

    /**
     * Builds the search index using Grunt.
     *
     * @return string The rendering result.
     */
    public function actionBuildSearch()
    {
        try {
            $output = $this->gruntService->buildSearch();
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
     * @param string|null $module The name of the module to migrate.
     * @return string The rendering result.
     */
    public function actionMigrateUp($module = null)
    {
        try {
            $output = $this->gruntService->migrateUp($module);
            return $this->renderPartial('migrate-up', ['output' => $output]);
        } catch (\Exception $e) {
            Yii::error('Error executing Grunt command: ' . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Error executing Grunt command: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }
}
