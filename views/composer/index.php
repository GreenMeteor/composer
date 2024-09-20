<?php

use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\Button;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Composer Update';

// Include requirements.php to access $requirements
$requirements = require Yii::getAlias('@composer/requirements.php');

// Custom CSS for alerts
$this->registerCss("
    .alert {
        margin-bottom: 15px;
    }
");

?>

<div class="container">
    <!-- Check server requirements -->
    <?php if (!empty($requirements)): ?>
        <div class="alert alert-danger">
            <strong>Error!</strong> Your server does not meet the following requirements:
            <ul>
                <?php foreach ($requirements as $requirement): ?>
                    <li><?= Html::encode($requirement) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="alert alert-success">
            <strong>Success!</strong> Your server meets all requirements!
        </div>
    <?php endif; ?>

    <!-- Panel for Composer and Git options -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <!-- Git Pull button -->
            <?= Button::asLink(Icon::get('git'))
                ->link(Url::to(['/composer/git/pull']))
                ->cssClass('pull-right btn btn-default')
                ->tooltip('Git Pull') ?>

            <!-- Grunt Task button -->
            <?= Button::asLink(Icon::get('file-code-o'))
                ->link(Url::to(['/composer/grunt/index']))
                ->cssClass('pull-right btn btn-default')
                ->tooltip('Grunt Tasks') ?>

            <!-- Page title -->
            <?= Html::tag('h1', 'Composer Update'); ?>
        </div>
        <div class="panel-body">
            <!-- Composer command form -->
            <?php $form = ActiveForm::begin(['action' => Url::to(['/composer/index']), 'method' => 'post']); ?>

            <!-- Command selection dropdown -->
            <select name="option" class="form-control">
                <option value="self-update">Self-Update</option>
                <option value="update">Update</option>
                <option value="install">Install</option>
            </select>

            <!-- Submit button -->
            <br>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>

            <!-- Output from the Composer command -->
            <?php if (isset($output)): ?>
                <?= Html::tag('h2', 'Output'); ?>
                <?= Html::tag('pre', Html::encode($output)); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
