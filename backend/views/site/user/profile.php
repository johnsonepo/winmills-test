<?php

/** @var yii\web\View $this */
/** @var common\models\User $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Profile';
?>

<div class="site-profile">
    <div class="profile-form w-1/3">
    
        <div class="">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
        <?php $form = ActiveForm::begin(['action' => ['profile/update']]); ?>
        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'password_hash')->passwordInput(['value' => '']) ?>

        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
