<?php

use yii\helpers\Html;

$this->title = 'Git Pull';
$this->params['breadcrumbs'][] = ['label' => 'Composer', 'url' => ['/composer']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="composer-git-pull">
    <?= Html::tag('h1', Html::encode($this->title)); ?>

    <?= $message ?>

    <?php if (!empty($output)): ?>
        <?= Html::tag('h2', 'Output'); ?>
        <?= Html::tag('pre', implode("\n", $output)); ?>
  <?php endif; ?>
</div>
