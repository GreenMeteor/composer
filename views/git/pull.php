<?php

use yii\helpers\Html;

$this->title = 'Git Pull';
$this->params['breadcrumbs'][] = ['label' => 'Composer', 'url' => ['/composer']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Html::tag('h1', Html::encode($this->title)); ?>
    </div>

    <div class="panel-body">
        <?= Html::tag('h2', 'Result'); ?>
        <p><?= $message ?></p>
        
        <?php if (!empty($output)): ?>
            <?= Html::tag('h2', 'Output'); ?>
            <pre><?= Html::encode(implode("\n", $output)) ?></pre>
        <?php endif; ?>
    </div>
</div>
