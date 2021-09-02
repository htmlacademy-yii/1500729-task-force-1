<?php
/* @var $this yii\web\View */
/* @var $model \frontend\models\Registration */
/* @var $reviews \frontend\models\Reviews */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="registration__user">
            <h1>Регистрация аккаунта</h1>
            <div class="registration-wrapper">
                <?php $form = ActiveForm::begin([
                    'method' => 'post',
                    'options' => ['class' => 'registration__user-form form-create']
            ]) ?>
                <div class="field-container field-container--registration">
                    <?= $form->field($model, 'email')->textInput(['class' => 'input textarea', 'placeholder' => 'kumarm@mail.ru'])
                        ->label('Электронная почта') ?>
                </div>
                <div class="field-container field-container--registration">
                    <?= $form->field($model, 'name')->textInput(['class' => 'input textarea', 'placeholder' => 'Мамедов Кумар'])
                        ->label('Ваше имя') ?>
                </div>
                <div class="field-container field-container--registration">
                    <?= $form->field($model, 'location')
                    ->widget(Select2::class, [
                        'data' => $model->getLocations(),
                        'options' => [
                            'placeholder' => 'Выберите город',
                            'class' => 'multiple-select input town-select registration-town'
                        ]
                    ])->label('Город проживания')?>
                </div>
                <div class="field-container field-container--registration">
                    <?= $form->field($model, 'password')
                        ->textInput(['class' => 'input textarea', 'type' => 'password'])
                        ->label('Пароль')->error(['class' => "registration__text-error"]) ?>
                </div>
                <?= Html::submitButton('Cоздать аккаунт', ['class' => 'button button__registration']) ?>
                <?php ActiveForm::end() ?>
            </div>
        </section>
    </div>
</main>


