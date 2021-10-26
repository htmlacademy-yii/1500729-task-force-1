<?php
/* @var $this yii\web\View */
/* @var $taskForm \frontend\models\Registration */

/* @var $categories \frontend\models\Categories */


use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="create__task">
            <h1>Публикация нового задания</h1>
            <div class="create__task-main">

                <?php $form = ActiveForm::begin([
                    'method' => 'post',
                    'id' => 'task-form',
                    'enableClientValidation' => false,
                    'validateOnSubmit' => true,
                    'options' => [
                        'enctype' => 'multipart/form-data',
                        'class' => 'create__task-form form-create',
                    ],
                    'fieldConfig' => [
                        'template' => "{label}{input}\n<span>{hint}</span>\n{error}",
                        'inputOptions' => [
                            'style' => 'width: 100%;',
                        ],
                        'errorOptions' => ['tag' => 'span',
                            'style' => 'color: red',
                            'class' => 'registration__text-error'
                        ]]
                ]) ?>
                <div class="field-container">
                    <?= $form->field($taskForm, 'title')->
                    textInput(['class' => 'input textarea', 'placeholder' => "Повесить полку"])->
                    label('Мне нужно')->hint('Кратко опишите суть работы') ?>
                </div>

                <div class="field-container">
                    <?= $form->field($taskForm, 'description')
                        ->textarea(['class' => 'input textarea', 'rows' => 7, 'placeholder' => "Введите Ваш текст"])
                        ->label('Подробности задания')
                        ->hint('Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться')
                    ?>
                </div>

                <div class="field-container">

                    <?= $form->field($taskForm, 'category_id')
                        ->dropDownList(ArrayHelper::map($categories, 'id', 'title'),
                            ['class' => 'multiple-select input multiple-select-big'])
                        ->label('Категория')->hint('Выберите категорию')
                    ?>
                </div>

                <?= $form->field($taskForm, 'files', ['options' => ['tag' => false]
                    , 'template' => "{label}\n<span>{hint}</span>\n<div class='create__file dz-clickable'>{input}\n<span>Добавить новый файл</span>\n{error}\n</div>"
                    , 'inputOptions' => ['style' => "display:none"]
                ])
                    ->hint('Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу')
                    ->label('Файлы') ?>

                <div class="field-container">
                    <?= $form->field($taskForm, 'address')
                        ->textInput(['id' => 'autoComplete',
                            'class' => 'input-navigation input-middle input',
                            'placeholder' => "Санкт-Петербург, Калининский район"])
                        ->label('Локация')
                        ->hint('Укажите адрес исполнения, если задание требует присутствия') ?>

                    <?= $form->field($taskForm, 'coordinates')->
                        textInput(['id' => "coordinates",
                        'type' => "hidden"])->label(false) ?>
                </div>

                <div class="create__price-time">
                    <div class="field-container create__price-time--wrapper">
                        <?= $form->field($taskForm, 'budget')
                            ->textInput(['class' => 'input textarea input-money', 'placeholder' => '1000'])
                            ->label('Бюджет')
                            ->hint('Не заполняйте для оценки исполнителем') ?>
                    </div>


                    <div class="field-container create__price-time--wrapper">
                        <?= $form->field($taskForm, 'due_date',
                        )
                            ->textInput(['class' => 'input-middle input input-date', 'placeholder' => '10.11.2021'])
                            ->label('Сроки исполнения')
                            ->hint('Укажите крайний срок исполнения') ?>
                    </div>

                    <?php ActiveForm::end() ?>
                </div>

                <div class="create__warnings">
                    <div class="warning-item warning-item--advice">
                        <h2>Правила хорошего описания</h2>
                        <h3>Подробности</h3>
                        <p>Друзья, не используйте случайный<br>
                            контент – ни наш, ни чей-либо еще. Заполняйте свои
                            макеты, вайрфреймы, мокапы и прототипы реальным
                            содержимым.</p>
                        <h3>Файлы</h3>
                        <p>Если загружаете фотографии объекта, то убедитесь,
                            что всё в фокусе, а фото показывает объект со всех
                            ракурсов.</p>
                    </div>
                    <?php if ($taskForm->errors): ?>
                        <div class="warning-item warning-item--error">
                            <h2>Ошибки заполнения формы</h2>
                            <?php foreach ($taskForm->errors as $label => $errors): ?>
                                <h3> <?= $taskForm->getAttributeLabel($label) ?></h3>

                                <p><?php foreach ($errors as $error): ?>
                                        <?= $error ?><br>
                                    <?php endforeach; ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?= Html::submitButton('Опубликовать', ['class' => 'button', 'form' => 'task-form']) ?>

        </section>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.6/dist/autoComplete.min.js"></script>
<script>
    const autoCompleteJS = new autoComplete({
        wrapper: false,
        data: {
            src: async (query) => {
                try {
                    const sourse = await fetch(`http://taskforce.loc/geo/index?query=${query}`);
                    return await sourse.json();
                } catch (error) {
                    return error;
                }
            },
            keys: ["adress"]

        },
        searchEngine: "loose",
        debounce: 500,
        threshold: 5,
        events: {
            input: {
                selection: (event) => {
                    const selection = event.detail.selection.match;
                    const coordinates = event.detail.selection.value.coordinates;
                    autoCompleteJS.input.value = selection;
                    document.getElementById('coordinates').value = coordinates;
                }
            }
        }
    });
    document.querySelector("#autoComplete").addEventListener("response", function (event) {
        // "event.detail" carries the returned data values
        console.log(event.detail)
    });
    document.querySelector("#autoComplete").addEventListener("selection", function (event) {
        // "event.detail" carries the autoComplete.js "feedback" object
        console.log(event.detail);
    });
</script>

