<?php
/** @var \frontend\models\Notifications $notification */

use yii\helpers\Html;

?>

<p>Задание <?= Html::a($notification->task->title, ['tasks/view', 'id' => $notification->task->id], ['class' => "link-regular"]) ?> завершено</p>
