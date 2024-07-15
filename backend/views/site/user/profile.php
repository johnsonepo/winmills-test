<?php

/** @var yii\web\View $this */
/** @var common\models\User $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Profile';
?>

<div class="site-profile">
    <div class="profile-form sm:w-full md:w-1/2 lg:w-1/3 mx-auto">

        <div class="">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>

        <?php $form = ActiveForm::begin(['action' => ['profile/update']]); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email']) ?>
        <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true, 'value' => ''])->label('Password') ?>

        <div class="form-group pt-4">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
