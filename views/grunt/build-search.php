<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $output array */

$this->title = 'Build Search';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="build-search" class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <?= Html::encode($this->title) ?>
        </div>
    </div>
    <div class="panel-body">
        <?php if (isset($output)): ?>
            <?= Html::tag('h2', 'Output'); ?>
            <?= Html::tag('pre', implode("\n", $output)); ?>
        <?php endif; ?>
    </div>
</div>
