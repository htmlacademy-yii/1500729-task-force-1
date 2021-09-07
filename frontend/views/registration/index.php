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
                    'options' => ['class' => 'registration__user-form form-create'],
                     'validateOnSubmit' => true,
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'inputOptions' => [
                        'class' => 'input textarea',
                        'style' => 'width: 100%;',
                    ],
                    'errorOptions' => ['tag' => 'span',
                        'style' => 'color: red',
                        'class' => 'registration__text-error'
                    ],
                    'labelOptions' => ['class' => null],
                ]
            ]) ?>

                    <?= $form->field($model, 'email', ['options' => ['class' => 'field-container field-container--registration']])->textInput(['placeholder' => 'kumarm@mail.ru'])
                        ->label('Электронная почта') ?>


                    <?= $form->field($model, 'name')->textInput(['class' => 'input textarea', 'placeholder' => 'Мамедов Кумар'])
                        ->label('Ваше имя') ?>

                    <?= $form->field($model, 'location')
                    ->widget(Select2::class, [
                        'data' => $model->getLocations(),
                        'options' => [
                            'placeholder' => 'Выберите город',
                            'class' => 'multiple-select input town-select registration-town'
                        ]
                    ])->label('Город проживания')?>

                    <?= $form->field($model, 'password')
                        ->textInput(['class' => 'input textarea', 'type' => 'password'])
                        ->label('Пароль')->error(['class' => "registration__text-error"]) ?>

                <?= Html::submitButton('Cоздать аккаунт', ['class' => 'button button__registration']) ?>
                <?php ActiveForm::end() ?>
            </div>
        </section>
    </div>
</main>


