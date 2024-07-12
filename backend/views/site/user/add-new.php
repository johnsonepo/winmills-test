<?php

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\rbac\Role[] $roles */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Add New User';
?>

<div class="user-create">

    <div class="user-form w-1/3">

    <?php if (Yii::$app->session->hasFlash('success')): ?>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        
    <?php endif; ?>

        <?php $form = ActiveForm::begin(['action' => ['user/create']]); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'value' => ''])?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'value' => ''])?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'value' => '']) ?>
        
        <?= $form->field($model, 'role')->dropDownList(
            \yii\helpers\ArrayHelper::map($roles, 'name', 'name'),
            ['prompt' => 'Select Role']
        ) ?>

        <?= $form->field($model, 'status')->dropDownList([
            9 => 'Inactive',
            10 => 'Active',
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
