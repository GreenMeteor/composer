<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $output array */

$this->title = 'Build Theme';
$this->params['breadcrumbs'][] = $this->title;

// Register PJAX library
$this->registerJsFile('@web/static/js/jquery.pjax.modified.js', ['position' => View::POS_HEAD]);
?>

<div id="build-theme" class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <?= Html::encode($this->title) ?>
        </div>
    </div>
    <div class="panel-body" data-ui-widget="pjax-container">
        <div id="output-container">
            <?php if (isset($output) && !empty($output)): ?>
                <?= Html::tag('h2', 'Output'); ?>
                <?= Html::tag('pre', Html::encode(implode("\n", $output))); ?>
            <?php endif; ?>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'run-command-form']); ?>
        
        <div class="form-group">
            <?= Html::submitButton('Run Command', ['id' => 'run-command-btn', 'class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
// Register JS to handle the form submission
$this->registerJs('
    $(document).on("submit", "#run-command-form", function(event) {
        event.preventDefault();
        var $form = $(this);
        var $btn = $form.find(":submit");
        var formData = $form.serialize();
        
        $btn.button("loading");
        
        $.ajax({
            type: "POST",
            url: "' . Yii::$app->urlManager->createUrl(['/composer/grunt/build-theme']) . '",
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Update the container with the command output
                    $("#output-container").html("<h2>Output</h2><pre>" + Html::encode(response.output.join(\"\\n\")) + "</pre>");
                } else {
                    console.error(response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            },
            complete: function() {
                $btn.button("reset");
            }
        });
    });
');
?>
