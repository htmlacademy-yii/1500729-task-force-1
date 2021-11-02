<?php
/* @var $this yii\web\View */
/* @var $users \frontend\models\Users[] */
/* @var $model \frontend\models\FilterUsers */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $categories \frontend\models\Categories */

use taskforce\app\StarsWidget;
use taskforce\helpers\PluralHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use frontend\controllers\UsersController;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

$this->title = 'Задания';
?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="user__search">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_users',
                'itemOptions' => [
                    'tag' => false,
                ],
                'options' => [
                    'tag' => false,
                ],
                'layout' => "

                        {items}

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
                <?php  $form = ActiveForm::begin([
                    'action' => ['users/index'],
                    'method' => 'get',
                    'options' => ['class' => 'search-task__form']
                    ]) ?>
                    <fieldset class="search-task__categories">
                        <legend>Категории</legend>

                        <?= $form->field($model, 'category_id')
                            ->checkboxList(ArrayHelper::map($categories, 'id', 'title'),
                            [
                                'item' => function($index, $label, $name, $checked, $value) use ($model)
                            {

                                   $checked = $checked ? 'checked' : '';
                                return '<label class="checkbox__legend">
                                             <input class="visually-hidden checkbox__input" id="'.$index.'" type="checkbox" name="'.$name.'" value='.$value.' '.$checked.'>
                                             <span>'. $label .'</span>
                                             </label>';
                            }])->label(false) ?>
                    </fieldset>
                <fieldset class="search-task__categories">
                    <legend>Дополнительно</legend>
                    <?= $form->field($model, 'options')
                        ->checkboxList($model->getOptions(),
                            [
                                'item' => function($index, $label, $name, $checked, $value) use ($model)
                                {

                                    $checked = $checked ? 'checked' : '';
                                    return '<label class="checkbox__legend">
                                             <input class="visually-hidden checkbox__input" id="'.$index.'" type="checkbox" name="'.$name.'" value='.$value.' '.$checked.'>
                                             <span>'. $label .'</span  >
                                             </label>';
                                }])->label(false) ?>
                </fieldset>
                <?= $form->field($model, 'search', ['options' => ['tag' => false], ]
                    )->textInput(['class' => 'input-middle input', 'type' => 'search'])->label('Поиск по имени', ['class' => 'search-task__name']) ?>
                <?= Html::submitButton('Искать', ['class' => 'button'])?>
                            <?php ActiveForm::end() ?>
                <?php \yii\widgets\Pjax::end() ?>
            </div>
        </section>
    </div>
</main>
