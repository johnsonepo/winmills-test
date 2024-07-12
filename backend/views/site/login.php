<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
?>
<div class="site-login bg-dark">
    <div class="container">
        <div class="row justify-content-center rounded bg-light p-4">
            <div class="col-lg-4 shadow border p-4">
                <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

                <p class="text-center">Please fill out the following fields to login:</p>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div class="form-group text-center">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block mt-4 px-4', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


