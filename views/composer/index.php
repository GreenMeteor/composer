<?php

use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\Button;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Button::asLink(Icon::get('git'))->link([Url::to('/composer/git/pull')])->cssClass('pull-right btn btn-default')->tooltip('Git') ?>
            <?= Html::tag('h1', 'Composer Update'); ?>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['action' => ['composer/index'], 'method' => 'post']); ?>

            <select name="option" class="form-control">
                <option value="self-update">Self-Update</option>
                <option value="update">Update</option>
                <option value="install">Install</option>
            </select>
            <br>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>

            <?= Html::tag('h2', 'Output'); ?>
            <?= Html::tag('pre', $output); ?>
        </div>
    </div>
</div>
