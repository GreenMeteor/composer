<?php

use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use yii\widgets\ActiveForm;

/** @var \humhub\modules\composer\models\EditForm $model */
?>

<?php ModalDialog::begin(['header' => Yii::t('ComposerModule.base', 'Edit composer.json')]) ?>

    <?php $form = ActiveForm::begin(['id' => 'composer-form']); ?>
    <div class="modal-body">
        <?= $form->field($model, 'composerData')->textarea(['rows' => 10]) ?>
    </div>

    <div class="modal-footer">
        <?= ModalButton::submitModal(null, Yii::t('ComposerModule.base', 'Save'), ['class' => 'btn btn-primary']) ?>
        <?= ModalButton::cancel() ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php ModalDialog::end() ?>
