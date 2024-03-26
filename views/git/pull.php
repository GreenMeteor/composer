<?php

use yii\helpers\Html;

$this->title = 'Git Pull';
$this->params['breadcrumbs'][] = ['label' => 'Composer', 'url' => ['/composer']];
$this->params['breadcrumbs'][] = $this->title;

// Include requirements.php to access $requirements
$requirements = require Yii::getAlias('@composer/requirements.php');
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Html::tag('h1', Html::encode($this->title)); ?>
    </div>

    <div class="panel-body">
        <?php if (!empty($requirements)): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Error!</strong> Your server does not meet the following requirements:
                <ul>
                    <?php foreach ($requirements as $requirement): ?>
                        <li><?= Html::encode($requirement) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success" role="alert">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($output)): ?>
            <div class="output-container">
                <?= Html::tag('h2', 'Output'); ?>
                <pre><?= Html::encode(implode("\n", $output)) ?></pre>
            </div>
        <?php endif; ?>
    </div>
</div>