<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $output array */

$this->title = 'Build Theme';
$this->pageTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="build-theme" class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <?= Html::encode($this->title) ?>
        </div>
    </div>
    <div class="panel-body" data-ui-widget="pjax-container">
        <?php if(isset($output)): ?>
            <?= Html::tag('h2', 'Output'); ?>
            <?= Html::tag('pre', implode("\n", $output)); ?>
        <?php endif; ?>

        <!-- Button to trigger the command -->
        <?= Html::button('Run Command', [
            'class' => 'btn btn-primary',
            'data' => [
                'pjax' => 1, // Enable PJAX for the button click
                'action' => '/composer/grunt/build-theme',
            ],
        ]) ?>
    </div>
</div>

<?php
// Register JS to handle the PJAX button click
$this->registerJs('
    $(document).on("click", "[data-ui-widget=pjax-container] [data-pjax=1]", function(event) {
        event.preventDefault();
        var $this = $(this);
        var container = $this.closest("[data-ui-widget=pjax-container]");
        $.ajax({
            type: "POST",
            url: $this.data("action"),
            data: "' . Yii::$app->request->csrfParam . '=' . Yii::$app->request->csrfToken . '",
            success: function(response) {
                container.html(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
');
?>