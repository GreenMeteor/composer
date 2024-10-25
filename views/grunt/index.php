<?php

use yii\helpers\Html;
use kartik\alert\Alert;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\composer\widgets\GruntBuildWidget;

/* @var $this \yii\web\View */
/* @var $content string */

$this->title = 'Manage Grunt Builders';
$this->pageTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <h4><?= Html::encode($this->title) ?></h4>
            <div class="help-block">
                <?= Yii::t('ComposerModule.base', 'Here you can manage different aspects of the Grunt builder.'); ?>
            </div>

            <?= Alert::widget([
                'options' => [
                    'class' => 'alert-warning',
                ],
                'body' => 'This is meant for development instances. Do not use in production!',
                'closeButton' => false,
            ]) ?>

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="nav-item active">
                    <a href="#build-assets" class="nav-link" data-toggle="tab"><?= Yii::t('ComposerModule.base', 'Build Assets') ?></a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#build-search" class="nav-link" data-toggle="tab"><?= Yii::t('ComposerModule.base', 'Build Search') ?></a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#migrate-up" class="nav-link" data-toggle="tab"><?= Yii::t('ComposerModule.base', 'Migrate Up') ?></a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="build-assets">
                    <?= Html::beginForm(['grunt/build-assets'], 'post', ['class' => 'form-inline']) ?>
                        <?= Html::submitButton('Run Build Assets', ['class' => 'btn btn-primary']) ?>
                    <?= Html::endForm() ?>
                    <?= GruntBuildWidget::widget(['task' => 'build-assets']) ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="build-search">
                    <?= Html::beginForm(['grunt/build-search'], 'post', ['class' => 'form-inline']) ?>
                        <?= Html::submitButton('Run Build Search', ['class' => 'btn btn-primary']) ?>
                    <?= Html::endForm() ?>
                    <?= GruntBuildWidget::widget(['task' => 'build-search']) ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="migrate-up">
                    <?= Html::beginForm(['grunt/migrate-up'], 'post', ['class' => 'form-inline']) ?>
                        <?= Html::submitButton('Run Migrate Up', ['class' => 'btn btn-primary']) ?>
                    <?= Html::endForm() ?>
                    <?= GruntBuildWidget::widget(['task' => 'migrate-up']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
