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
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        
        <div class="form-group field-user-password_hash">
            <label class="control-label" for="user-password_hash">Password</label>
            <div class="input-group">
                <?= Html::activePasswordInput($model, 'password_hash', ['class' => 'form-control', 'id' => 'password-input']) ?>
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="toggle-show-password">
                        <svg class="w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" className="size-6">
                        <path d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z" />
                        <path d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0 1 15.75 12ZM12.53 15.713l-4.243-4.244a3.75 3.75 0 0 0 4.244 4.243Z" />
                        <path d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 0 0-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 0 1 6.75 12Z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="help-block"></div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$js = <<<JS
document.getElementById('toggle-show-password').addEventListener('click', function () {
    let passwordInput = document.getElementById('password-input');
    let type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
});
JS;
$this->registerJs($js);
?>
