<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\alert\Alert;
use humhub\widgets\Tabs;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\composer\widgets\GruntBuildWidget;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $output string Output from the Grunt command, if any */
/* @var $taskExecuted string The task that was executed, if any */

$this->title = 'Manage Grunt Builders';
$this->pageTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;

// Get active tab from request
$activeTab = Yii::$app->request->get('tab', 'build-assets');
?>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="panel-body">
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

            <?= Tabs::widget([
                'options' => ['id' => 'grunt-tabs'],
                'items' => [
                    [
                        'label' => Yii::t('ComposerModule.base', 'Build Assets'),
                        'url' => Url::to(['index', 'tab' => 'build-assets']),
                        'active' => $activeTab == 'build-assets',
                    ],
                    [
                        'label' => Yii::t('ComposerModule.base', 'Build Search'),
                        'url' => Url::to(['index', 'tab' => 'build-search']),
                        'active' => $activeTab == 'build-search',
                    ],
                    [
                        'label' => Yii::t('ComposerModule.base', 'Migrate Up'),
                        'url' => Url::to(['index', 'tab' => 'migrate-up']),
                        'active' => $activeTab == 'migrate-up',
                    ],
                ]
            ]); ?>

            <div class="tab-content">
                <?php if ($activeTab == 'build-assets'): ?>
                    <div class="tab-pane active">
                        <h5><?= Yii::t('ComposerModule.base', 'Build Assets') ?></h5>
                        <?= Html::beginForm(['index', 'tab' => 'build-assets'], 'post', ['class' => 'form-inline']) ?>
                            <?= Html::hiddenInput('task', 'build-assets') ?>
                            <?= Html::submitButton('Run Build Assets', ['class' => 'btn btn-primary']) ?>
                        <?= Html::endForm() ?>
                        
                        <?php if ($activeTab == 'build-assets' && isset($taskExecuted) && $taskExecuted == 'build-assets'): ?>
                            <div class="grunt-output">
                                <br>
                                <?= $output ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif ($activeTab == 'build-search'): ?>
                    <div class="tab-pane active">
                        <h5><?= Yii::t('ComposerModule.base', 'Build Search') ?></h5>
                        <?= Html::beginForm(['index', 'tab' => 'build-search'], 'post', ['class' => 'form-inline']) ?>
                            <?= Html::hiddenInput('task', 'build-search') ?>
                            <?= Html::submitButton('Run Build Search', ['class' => 'btn btn-primary']) ?>
                        <?= Html::endForm() ?>
                        
                        <?php if ($activeTab == 'build-search' && isset($taskExecuted) && $taskExecuted == 'build-search'): ?>
                            <div class="grunt-output">
                                <br>
                                <?= $output ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="tab-pane active">
                        <h5><?= Yii::t('ComposerModule.base', 'Migrate Up') ?></h5>
                        <?= Html::beginForm(['index', 'tab' => 'migrate-up'], 'post', ['class' => 'form-inline']) ?>
                            <?= Html::hiddenInput('task', 'migrate-up') ?>
                            <?= Html::submitButton('Run Migrate Up', ['class' => 'btn btn-primary']) ?>
                        <?= Html::endForm() ?>

                        <?php if ($activeTab == 'migrate-up' && isset($taskExecuted) && $taskExecuted == 'migrate-up'): ?>
                            <div class="grunt-output">
                                <br>
                                <?= $output ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
