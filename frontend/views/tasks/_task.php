<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="new-task__card">
    <div class="new-task__title">
        <?= Html::a('<h2>' . Html::encode($model->title) . '</h2>', ['tasks/view', 'id' => $model->id], ['class' => 'link-regular'])?>
        <?= Html::a('<p>'. $model->category->title . '</p>', ['tasks/index', 'category_id' => $model->category->id], ['class' => 'new-task__type link-regular'])?>

    </div>
    <div class="new-task__icon new-task__icon--<?= $model->category->icon ?>"></div>
    <p class="new-task_description">
        <?= Html::encode($model->description) ?>
    </p>
    <?php if ($model->budget): ?>
        <b class="new-task__price new-task__price--translation"><?= Html::encode($model->budget) ?><b>
                ₽</b></b>
    <?php endif; ?>
    <?php if ($model->location): ?>
        <p class="new-task__place"><?= Html::encode($model->address) ?></p>
    <?php endif; ?>
    <span
        class="new-task__time"><?= Yii::$app->formatter->format($model->dt_add, 'relativeTime') ?></span>
</div>
