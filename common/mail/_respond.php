<?php
/** @var \frontend\models\Notifications $notification */
use yii\helpers\Html;

?>

<p>Новый отклик к заданию <?= Html::a($notification->task->title, ['tasks/view', 'id' => $notification->task->id], ['class' => "link-regular"]) ?></p>
