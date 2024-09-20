<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\widgets\Button;
use humhub\modules\ui\icon\widgets\Icon;

$this->title = 'Git Pull';
$this->params['breadcrumbs'][] = ['label' => 'Composer', 'url' => ['/composer']];
$this->params['breadcrumbs'][] = $this->title;

// Include requirements.php for server requirements
$requirements = require Yii::getAlias('@composer/requirements.php');
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <!-- Composer button -->
        <?= Button::asLink(Icon::get('music'))
            ->link(Url::to(['/composer/composer/index']))
            ->cssClass('pull-right btn btn-default')
            ->tooltip('Composer') ?>

        <!-- Grunt button -->
        <?= Button::asLink(Icon::get('file-code-o'))
            ->link(Url::to(['/composer/grunt/index']))
            ->cssClass('pull-right btn btn-default')
            ->tooltip('Grunt Tasks') ?>

        <!-- Page title -->
        <?= Html::tag('h1', Html::encode($this->title)); ?>
    </div>

    <div class="panel-body">
        <!-- Display success flash messages -->
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success" role="alert">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <!-- Display error flash messages -->
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <!-- Output from the Git pull command -->
        <?php if (!empty($output)): ?>
            <div class="output-container">
                <?= Html::tag('h2', 'Output'); ?>
                <pre><?= Html::encode(implode("\n", $output)) ?></pre>
            </div>
        <?php endif; ?>

        <!-- Git Pull form -->
        <?= Html::beginForm(['pull'], 'post') ?>

            <!-- Branch selection dropdown -->
            <?= Html::dropDownList('branch', 'master', [
                'master' => 'Master',
                'develop' => 'Develop'
            ], ['class' => 'form-control']) ?>
            
            <br><br>
            
            <!-- Submit button for the pull operation -->
            <?= Html::submitButton('Pull from Git Repository', ['class' => 'btn btn-primary']) ?>
            
            <!-- CSRF hidden input -->
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
            
        <?= Html::endForm() ?>
    </div>
</div>
