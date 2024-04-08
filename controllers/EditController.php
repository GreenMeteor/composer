<?php

namespace humhub\modules\composer\controllers;

use Yii;
use yii\helpers\Url;
use humhub\widgets\ModalClose;
use yii\web\NotFoundHttpException;
use humhub\modules\composer\models\EditForm;
use humhub\modules\admin\components\Controller;

/**
 * EditController handles the editing of composer.json file.
 */
class EditController extends Controller
{
    /**
     * Action to open the composer.json file in a modal.
     * @throws \yii\web\NotFoundHttpException if the composer.json file is not found
     * @return string|\yii\web\Response the rendering result
     */
    public function actionComposer()
    {
        $composerJsonFile = $_SERVER['DOCUMENT_ROOT'] . '/composer.json';

        if (!file_exists($composerJsonFile)) {
            throw new NotFoundHttpException('composer.json file not found.');
        }

        $model = new EditForm();
        $model->composerData = file_get_contents($composerJsonFile);

        if ($model->load(Yii::$app->request->post()) && $model->saveComposerData()) {
            return ModalClose::widget(['saved' => true]);
        }

        return $this->renderAjax('composer', [
            'model' => $model,
        ]);
    }
}
