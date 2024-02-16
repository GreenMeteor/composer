<?php

use humhub\modules\ui\form\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::tag('h1', 'Composer Update'); ?>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['action' => ['composer/index'], 'method' => 'post']); ?>

            <!-- Dropdown button with three options -->
            <div class="btn-group">
                <select name="option" class="form-control">
                    <option value="self-update">Self-Update</option>
                    <option value="update">Update</option>
                    <option value="install">Install</option>
                </select>
                
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <?php ActiveForm::end(); ?>

            <?= Html::tag('h2', 'Output'); ?>
            <?= Html::tag('pre', $output); ?>
        </div>
    </div>
</div>
