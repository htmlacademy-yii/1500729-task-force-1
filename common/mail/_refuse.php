<?php
/** @var \frontend\models\Notifications $notification */

use yii\helpers\Html;

?>

<p>Исполнитель отказался от выполнения задания <?= Html::a($notification->task->title, ['tasks/view', 'id' => $notification->task->id], ['class' => "link-regular"]) ?></p>
