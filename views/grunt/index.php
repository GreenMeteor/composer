<?php

use humhub\modules\ui\icon\widgets\Icon;
use yii\helpers\Html;
use kartik\alert\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <h4><?= Yii::t('ComposerModule.base', 'Manage Grunt Builders') ?></h4>
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
                <li role="presentation" class="nav-item active"> <!-- Add 'active' class here -->
                    <a href="#build-assets" class="nav-link" data-toggle="tab"><?= Yii::t('ComposerModule.base', 'Build Assets') ?></a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#build-theme" class="nav-link" data-toggle="tab"><?= Yii::t('ComposerModule.base', 'Build Theme') ?></a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#build-search" class="nav-link" data-toggle="tab"><?= Yii::t('ComposerModule.base', 'Build Search') ?></a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#migrate-up" class="nav-link" data-toggle="tab"><?= Yii::t('ComposerModule.base', 'Migrate Up') ?></a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="build-assets"> <!-- Add 'active' class here -->
                    <?= $this->render('build-assets') ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="build-theme">
                    <?= $this->render('build-theme') ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="build-search">
                    <?= $this->render('build-search') ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="migrate-up">
                    <?= $this->render('migrate-up') ?>
                </div>
            </div>
        </div>
    </div>
</div>
