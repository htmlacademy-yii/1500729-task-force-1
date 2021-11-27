<?php
/** @var \frontend\models\Tasks $model */

use taskforce\app\newMessageWidget;
use taskforce\app\StarsWidget;
use yii\helpers\Html;
if($model->executor_id) {
$stars = round($model->executor->calculateStars($model->executor_id),2);
}

$getStatus = $model->getStatus();
?>


<div class="new-task__card">
    <div class="new-task__title">
        <?=
        Html::a('<h2>' . Html::encode($model->title) . '</h2>', ['tasks/view', 'id' => $model->id],
            ['class' => 'link-regular']) ?>

        <?= Html::a('<p>' . $model->category->title . '</p>',
            ['tasks/index', 'category_id' => $model->category->id],
            ['class' => 'new-task__type link-regular']) ?>
    </div>

    <div class="task-status <?= array_key_first($getStatus) ?>-status"><?= array_shift($getStatus) ?></div>
    <p class="new-task_description">
        <?= $model->description ?>
    </p>
    <?php if ($model->executor_id): ?>
    <div class="feedback-card__top ">
        <a href="#"><img src="/img/man-glasses.jpg" width="36" height="36"></a>
        <div class="feedback-card__top--name my-list__bottom">
            <p class="link-name"><?= Html::a($model->executor->name,
                    ['users/view', 'id' => $model->executor_id],
                    ['class' => 'link-regular']) ?></p>
            <?= newMessageWidget::widget(['task_id' => $model->id])?>
            <?= StarsWidget::widget(['stars' => $stars]) ?>
            <b><?= $stars ?></b>
        </div>
    </div>
    <?php endif; ?>
</div>

