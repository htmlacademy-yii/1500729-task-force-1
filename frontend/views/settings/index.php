<?php
/** @var \frontend\models\SettingsForm $model
 * @var \frontend\models\Users $user
 */

use frontend\models\Categories;
use frontend\models\Registration;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="account__redaction-wrapper">
            <h1>Редактирование настроек профиля</h1>
            <?php $form = \yii\widgets\ActiveForm::begin([
                'method' => 'post'
            ]) ?>
            <div class="account__redaction-section">
                <h3 class="div-line">Настройки аккаунта</h3>
                <div class="account__redaction-section-wrapper">

                    <div class="account__redaction-avatar">
                        <img src="<?= $user->avatar->path ?>" width="156" height="156">

                        <?= $form->field($model, 'avatar', [
                            'options' => [
                                'tag' => false
                            ],
                            'template' => "{input}\n{label}\n{error}"
                        ])->fileInput(['id' => 'upload-avatar'])->label('Сменить аватар',['class' => 'link-regular']) ?>

                    </div>
                    <div class="account__redaction">
                        <div class="account__input account__input--name">
                            <?=
                            $form->field($model, 'name', ['options' => ['class' => 'field-container account__input account__input--name']])->textInput(['class' => 'input textarea', 'disabled' => true]
                            )->label('Ваше имя') ?>
                        </div>
                        <div class="account__input account__input--email">
                            <?=
                            $form->field($model, 'email', ['options' => ['class' => 'field-container account__input account__input--email']])->textInput(['class' => 'input textarea']
                            )->label('Email') ?>
                        </div>
                        <div class="account__input account__input--name">
                            <?= $form->field($model, 'location_id')
                                ->widget(Select2::class, [
                                    'data' => Registration::getLocations(),
                                    'options' => [
                                        'placeholder' => 'Выберите город',
                                        'class' => 'multiple-select input town-select registration-town'
                                    ]
                                ])->label('Город проживания') ?>
                        </div>

                        <?=
                        $form->field($model, 'birthday',
                            ['options' => ['class' => 'account__input account__input--date']])
                            ->input('date', ['class' => 'input-middle input input-date'])->
                            label('День Рождения') ?>
                        <?=
                        $form->field($model, 'information', [
                            'options' => [
                                'class' => 'account__input account__input--info'
                            ]
                        ])->textarea([
                            'class' => 'input textarea',
                            'rows' => 7,
                            'placeholder' => 'Place your text'
                        ])->label('Информация о себе')
                        ?>

                    </div>
                </div>
                <h3 class="div-line">Выберите свои специализации</h3>
                <div class="account__redaction-section-wrapper">
                    <?= $form->field($model, 'category_id', ['options' => ['class' => 'search-task__categories account_checkbox--bottom',
                    ],
                    ])
                        ->checkboxList(ArrayHelper::map($categories, 'id', 'title'), [
                            'tag' => false, 'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                                if (\frontend\models\ExecutorCategories::find()->where(['user_id' => Yii::$app->user->id])->
                                andWhere(['category_id' => $value])->one()) {
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                }
                                return '<label class="checkbox__legend">
                                             <input class="visually-hidden checkbox__input" id="' . $index . '" type="checkbox" name="' . $name . '" value=' . $value . ' ' . $checked . '>
                                             <span>' . $label . '</span>
                                             </label>';
                            }])->label(false);
                    ?>
                </div>
            </div>
            <h3 class="div-line">Безопасность</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <?= $form->field($model, 'password', [
                    'options' => [
                        'class' => 'account__input'
                    ]
                ])->passwordInput(['class' => 'input textarea'])->label('Новый пароль')
                ?>
                <?= $form->field($model, 'repeat_password', [
                    'options' => [
                        'class' => 'account__input'
                    ]
                ])->passwordInput(['class' => 'input textarea'])->label('Повтор пароля')
                ?>

            </div>

            <h3 class="div-line">Фото работ</h3>

            <?php if($user->workPhotos): ?>
                <?php foreach ($user->workPhotos as $photo): ?>
                    <img src="<?= $photo->file->path ?>" width="156" height="156" alt="Фото работы">
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="account__redaction-section-wrapper account__redaction">

                <span class="dropzone">Выбрать фотографии</span>
            </div>

            <h3 class="div-line">Контакты</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <?=
                $form->field($model, 'phone', [
                    'options' => [
                        'class' => 'account__input'
                    ]
                ])->textInput(['class' => 'input textarea',
                    'placeholder' => '8 (555) 187 44 87'])->
                label('Телефон')
                ?>

                <?=
                $form->field($model, 'skype', [
                    'options' => [
                        'class' => 'account__input'
                    ]
                ])->textInput(['class' => 'input textarea',
                    'placeholder' => 'DenisT'])->
                label('Skype')
                ?>

                <?=
                $form->field($model, 'other_contact', [
                    'options' => [
                        'class' => 'account__input'
                    ]
                ])->textInput(['class' => 'input textarea',
                    'placeholder' => '@DenisT'])->
                label('Telegram')
                ?>

            </div>
            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <?=
                    $form->field($model, 'notice_new_message', [
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox([
                        'class' => 'visually-hidden checkbox__input', 'label' => "<span>Новое сообщение</span>",
                        'labelOptions' => [
                            'class' => 'checkbox__legend'
                        ], 'checked' => $model->notice_new_message == 1])
                    ?>

                    <?=
                    $form->field($model, 'notice_new_action', [
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox([
                        'class' => 'visually-hidden checkbox__input', 'label' => "<span>Действия по заданию</span>",
                        'labelOptions' => [
                            'class' => 'checkbox__legend'
                        ], 'checked' => $model->notice_new_action == 1])
                    ?>
                    <?=
                    $form->field($model, 'notice_new_review', [
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox([
                        'class' => 'visually-hidden checkbox__input', 'label' => "<span>Новый отзыв</span>",
                        'labelOptions' => [
                            'class' => 'checkbox__legend'
                        ], 'checked' => $model->notice_new_review == 1])
                    ?>

                </div>
                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <?=
                    $form->field($model, 'show_contacts', [
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox([
                        'class' => 'visually-hidden checkbox__input', 'label' => "<span>Показывать мои контакты только заказчику</span>",
                        'labelOptions' => [
                            'class' => 'checkbox__legend',

                        ], 'checked' => $model->show_contacts == 1])
                    ?>

                    <?=
                    $form->field($model, 'show_profile', [
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox([
                        'class' => 'visually-hidden checkbox__input', 'label' => "<span>Не показывать мой профиль</span>",
                        'labelOptions' => [
                            'class' => 'checkbox__legend'
                        ], 'checked' => $model->show_profile == 1])
                    ?>

                </div>
            </div>
            <?= \yii\helpers\Html::submitButton('Сохранить изменения', ['class' => 'button']) ?>
    </div>

    <?php \yii\widgets\ActiveForm::end() ?>



</main>


