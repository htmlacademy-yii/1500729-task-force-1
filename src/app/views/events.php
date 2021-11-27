<?php
/** @var \frontend\models\Notifications $events */

use yii\helpers\Html;

?>

<h3>Новые события</h3>
<?php if (!$events): ?>
<p> Новых уведомлений нет</p>
<?php else: ?>
<?php foreach ($events as $event): ?>
                <p class="lightbulb__new-task lightbulb__new-task--<?= $event->type ?>">
                    <?php $body = $event->getTypes(); ?>
<?= $body[$event->type] ?>
<?= Html::a($event->task->title, ['tasks/view', 'id' => $event->task->id], ['class' => "link-regular"]) ?>
<?php endforeach; ?>
                    <?php endif; ?>
