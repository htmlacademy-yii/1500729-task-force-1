<?php
/** @var \frontend\models\Notifications $notification */

use yii\helpers\Html;

?>

<p>Поздравляем! Вас выбрали исполнителем для задания <?= Html::a($notification->task->title, [['tasks/view', 'id' => $notification->task->id], ['class' => "link-regular"]]) ?></p>
