<?php
/* @var $this yii\web\View */
/* @var $users \frontend\models\Users[] */
/* @var $model \frontend\models\FilterUsers */

use taskforce\app\StarsWidget;
use taskforce\helpers\PluralHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use frontend\controllers\UsersController;
use yii\widgets\ActiveForm;

$this->title = 'Задания';
?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="user__search">
            <?php foreach ($users as $user): ?>
                <?php $count = 0;
                      $stars = round($user->calculateStars($user->id),2); ?>
            <div class="content-view__feedback-card user__search-wrapper">
                <div class="feedback-card__top">
                    <div class="user__search-icon">
                        <a href="user.html"><img src="/img/man-glasses.jpg" width="65" height="65"></a>
                        <span><?= PluralHelper::Plural(['заданий', 'задание', 'задание', 'задания', 'заданий', 'задания'],
                                                        count($user->executeTasks)) ?></span>
                        <?php foreach ($user->executeTasks as $task) {
                            $count = $count + count ($task->reviews);
                        } ?>
                        <span><?= PluralHelper::Plural(['отзывов', 'отзыв', 'отзыв', 'отзыва', 'отзывов', 'отзыва'],
                                                        $count) ?> </span>
                    </div>
                    <div class="feedback-card__top--name user__search-card">
                        <p class="link-name"><?= Html::a($user->name, ['users/view', 'id' => $user->id], ['class' => 'link-regular']) ?></p>
                        <?= StarsWidget::widget(['stars' => $stars]) ?>
                        <b><?= $stars ?></b>
                        <p class="user__search-content">
                            <?= Html::encode($user->information)?>
                        </p>
                    </div>
                    <span class="new-task__time">Был на сайте <?= Yii::$app->formatter->format($user->dt_last_activity, 'relativeTime')?></span>
                </div>
                <div class="link-specialization user__search-link--bottom">
                    <?php foreach ($user->executorCategories as $category): ?>
                    <a href="browse.html" class="link-regular"><?= $category->category->title ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
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
