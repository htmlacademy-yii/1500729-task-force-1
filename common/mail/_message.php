<?php

/** @var \frontend\models\Notifications $notification */

use yii\helpers\Html;

?>

<p>У Вас новое сообщение в чате <?= Html::a($notification->task->title, ['tasks/view', 'id' => $notification->task->id], ['class' => "link-regular"]) ?></p>
