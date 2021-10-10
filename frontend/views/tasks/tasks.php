<?php

/* @var $this yii\web\View
 * @var $model \frontend\models\FilterUsers
 * @var $tasks \frontend\models\Tasks
 * @var $categories \frontend\models\Categories
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Задания';
?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="new-task">
            <div class="new-task__wrapper">
                <h1>Новые задания</h1>
                <?php foreach ($tasks as $task): ?>
                    <div class="new-task__card">
                        <div class="new-task__title">
                            <?= Html::a('<h2>' . Html::encode($task->title) . '</h2>', ['tasks/view', 'id' => $task->id], ['class' => 'link-regular'])?>
                            <?= Html::a('<p>'. $task->category->title . '</p>',['tasks/index', 'category_id' => $task->category->id], ['class' => 'new-task__type link-regular'] )?>

                        </div>
                        <div class="new-task__icon new-task__icon--<?= $task->category->icon ?>"></div>
                        <p class="new-task_description">
                            <?= Html::encode($task->description) ?>
                        </p>
                        <?php if ($task->budget): ?>
                        <b class="new-task__price new-task__price--translation"><?= Html::encode($task->budget) ?><b>
                                ₽</b></b>
                        <?php endif; ?>
                        <?php if($task->location): ?>
                        <p class="new-task__place"><?= Html::encode($task->location->location) ?>
                            , <?= Html::encode($task->address) ?></p>
                        <?php endif; ?>
                        <span
                            class="new-task__time"><?= Yii::$app->formatter->format($task->dt_add, 'relativeTime') ?></span>
                    </div>

                <?php endforeach; ?>
            </div>
            <div class="new-task__pagination">
                <ul class="new-task__pagination-list">
                    <li class="pagination__item"><a href="#"></a></li>
                    <li class="pagination__item pagination__item--current">
                        <a>1</a></li>
                    <li class="pagination__item"><a href="#">2</a></li>
                    <li class="pagination__item"><a href="#">3</a></li>
                    <li class="pagination__item"><a href="#"></a></li>
                </ul>
            </div>
        </section>
        <section class="search-task">
            <div class="search-task__wrapper">
                <?php \yii\widgets\Pjax::begin() ?>
                <?php $form = ActiveForm::begin([
                    'action' => ['tasks/index'],
                    'method' => 'get',
                    'options' => ['class' => 'search-task__form']
                ]) ?>
                <fieldset class="search-task__categories">
                    <legend>Категории</legend>

                    <?= $form->field($model, 'category_id', ['options' => ['tag' => false]])
                        ->checkboxList(ArrayHelper::map($categories, 'id', 'title'),
                            [
                                'item' => function ($index, $label, $name, $checked, $value) use ($model) {

                                    $checked = $checked ? 'checked' : '';
                                    return '<label class="checkbox__legend">
                                             <input class="visually-hidden checkbox__input" id="' . $index . '" type="checkbox" name="'.$name.'" value=' . $value . ' ' . $checked . '>
                                             <span>' . $label . '</span>
                                             </label>';
                                }])->label(false) ?>
                </fieldset>
                <fieldset class="search-task__categories">
                    <legend>Дополнительно</legend>
                    <?= $form->field($model, 'options')
                        ->checkboxList($model->getOptions(),
                            [
                                'item' => function ($index, $label, $name, $checked, $value) use ($model) {

                                    $checked = $checked ? 'checked' : '';
                                    return '<label class="checkbox__legend">
                                             <input class="visually-hidden checkbox__input" id="' . $index . '" type="checkbox" name="' . $name . '" value=' . $value . ' ' . $checked . '>
                                             <span>' . $label . '</span  >
                                             </label>';
                                }])->label(false) ?>
                </fieldset>
                <?= $form->field($model, 'period', ['options' =>
                    ['class' => 'field-container']])->dropDownList($model->getDataTimes(), ['class' => 'multiple-select input', 'prompt' => ''])->label('Период', ['class' => 'search-task__name']) ?>
                <?= $form->field($model, 'search', ['options' => ['tag' => false],]
                )->textInput(['class' => 'input-middle input', 'type' => 'search'])->label('Поиск по названию', ['class' => 'search-task__name']) ?>
                <?= Html::submitButton('Искать', ['class' => 'button']) ?>
                <?php ActiveForm::end() ?>
            </div>
        </section>
    </div>
</main>
