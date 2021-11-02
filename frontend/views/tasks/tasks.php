<?php

/* @var $this yii\web\View
 * @var $model \frontend\models\FilterUsers
 * @var $tasks \frontend\models\Tasks
 * @var $categories \frontend\models\Categories
 *  @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

$this->title = 'Задания';
?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="new-task">
            <?=
            ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_task',
                'itemOptions' => [
                    'tag' => false,
                ],
                'options' => [
                    'tag' => false,
                ],
                'layout' => "<div class='new-task__wrapper'>
                    <h1>Новые задания</h1>
                        {items}
                 </div>
                 <div class='new-task__pagination'>
                 {pager}\n
                 </div>",
                'pager' => [

                    'options' => [
                        'class' => 'new-task__pagination-list',
                    ],
                    'prevPageLabel' => '<img src="/img/arrow.png">',
                    'nextPageLabel' => '<img src="/img/arrow.png">',
                    'prevPageCssClass' => 'pagination__item',
                    'nextPageCssClass' => 'pagination__item',
                    'pageCssClass' => 'pagination__item',
                    'activePageCssClass' => 'pagination__item--current',
                    'maxButtonCount' => 5
                ],
            ])
            ?>

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
