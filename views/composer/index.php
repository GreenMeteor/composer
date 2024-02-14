<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::tag('h1', 'Composer Update'); ?>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['action' => ['composer/index'], 'method' => 'post']); ?>
            <?= Html::submitButton('Run Command', ['class' => 'btn btn-primary']); ?>
            <?php ActiveForm::end(); ?>

            <?= Html::tag('h2', 'Output'); ?>
            <?= Html::tag('pre', $output); ?>
        </div>
    </div>
</div>
