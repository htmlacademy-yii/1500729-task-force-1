<?php
/* @var $this yii\web\View */
/* @var $task \frontend\models\Tasks */
/* @var $responds \frontend\models\Responds */
/* @var $respondAuthor \frontend\models\Responds */
/* @var $model \frontend\models\Responds */
/* @var $review \frontend\models\Reviews */
/** @var int $user_id */
/** @var int $countAuthorTasks */

use frontend\models\Tasks;
use frontend\models\Users;
use taskforce\app\RatioWidget;
use taskforce\app\StarsWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use taskforce\helpers\PluralHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerJsFile(
    'https://api-maps.yandex.ru/2.1/?apikey=' . Yii::$app->params['yandexGeocoder'] . '&lang=ru_RU',
      ['position' => \yii\web\View::POS_HEAD]);
$this->registerJs("
                                ymaps.ready(init);
                                function init(){

                                    var myMap = new ymaps.Map('map', {
                                        center: [$task->longitude, $task->latitude],
                                        zoom: 14
                                    }),
                                        myGeoObject = new ymaps.GeoObject({
                                            // Описание геометрии.
                                            geometry: {
                                                type: 'Point',
                                                coordinates:  [$task->longitude, $task->latitude]
                                            }
                                        });
                                    myMap.geoObjects.add(myGeoObject);
                                }", \yii\web\View::POS_READY);
?>
<main class="page-main">
    <div class="main-container page-container">
        <section class="content-view">
            <div class="content-view__card">
                <div class="content-view__card-wrapper">
                    <div class="content-view__header">
                        <div class="content-view__headline">
                            <h1><?= Html::encode($task->title) ?></h1>
                            <span>Размещено в категории
                                    <?= Html::a($task->category->title,
                                        ['tasks/index', 'category_id' => $task->category->id],
                                        ['class' => 'link-regular']) ?>
                                <?= Yii::$app->formatter->format($task->dt_add, 'relativeTime') ?></span>
                        </div>
                        <b class="new-task__price new-task__price--<?= $task->category->icon ?> content-view-price"><?= Html::encode($task->budget) ?>
                            <b> ₽</b></b>
                        <div
                            class="new-task__icon new-task__icon--<?= $task->category->icon ?> content-view-icon"></div>
                    </div>
                    <div class="content-view__description">
                        <h3 class="content-view__h3">Общее описание</h3>
                        <p>
                            <?= Html::encode($task->description) ?>
                        </p>
                    </div>
                    <?php if ($task->taskFiles): ?>
                        <div class="content-view__attach">

                            <h3 class="content-view__h3">Вложения</h3>
                            <?php foreach ($task->taskFiles as $file): ?>
                                <?= Html::a(Html::encode($file->file->name), [$file->file->path]) ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($task->address):?>
                    <div class="content-view__location">
                        <h3 class="content-view__h3">Расположение</h3>
                        <div class="content-view__location-wrapper">
                            <div class="content-view__map">
                                <div id="map" style="width: 361px; height: 292px"></div>
                            </div>

                            <?php if ($task->address): ?>
                                <div class="content-view__address">
                                    <span class="address__town"></span><br>
                                    <span><?= Html::encode($task->address) ?></span>
                                    <p>Вход под арку, код домофона 1122</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if (Yii::$app->user->identity->role === Users::ROLE_EXECUTOR || $task->author_id === $user_id): ?>
                    <div class="content-view__action-buttons">
                        <?php foreach ($task->getActions($user_id, $respondAuthor) as $action): ?>
                            <button class=" button button__big-color <?= array_key_first($action) ?>-button open-modal"
                                    type="button"
                                    data-for="<?= array_key_first($action) ?>-form"><?= array_shift($action) ?>
                            </button>
                        <?php endforeach; ?>

                    </div>
                <?php endif; ?>
            </div>
            <?php if ($task->responds && $task->author_id === $user_id || $respondAuthor): ?>
                <div class="content-view__feedback">
                    <h2>Отклики <span>(<?= count($task->responds) ?>)</span></h2>
                    <div class="content-view__feedback-wrapper">
                        <?php foreach ($task->responds as $respond): ?>
                            <?php if ($respond->executor_id === $user_id || $task->author_id === $user_id): ?>
                                <div class="content-view__feedback-card">
                                    <div class="feedback-card__top">
                                        <a href="user.html"><img src="/img/man-glasses.jpg" width="55" height="55"></a>
                                        <div class="feedback-card__top--name">
                                            <p><?= Html::a(Html::encode($respond->executor->name),
                                                    ['users/view', 'id' => $respond->executor->id], ['class' => 'link-regular']) ?></p>
                                            <?php $stars = Users::calculateStars($respond->executor->id) ?>
                                            <?= StarsWidget::widget(['stars' => $stars]) ?>
                                            <b><?= round($stars, 2) ?></b>
                                        </div>
                                        <span class="new-task__time">
                                <?= Yii::$app->formatter->format($respond->dt_add, 'relativeTime') ?>
                            </span>
                                    </div>
                                    <div class="feedback-card__content">
                                        <p>
                                            <?= Html::encode($respond->content) ?>
                                        </p>
                                        <span><?= Html::encode($respond->budget) ?> ₽</span>
                                    </div>
                                    <?php if ($task->author_id == $user_id && $task->status == $task::STATUS_NEW && !$respond->decline): ?>
                                        <div class="feedback-card__actions">
                                            <?= Html::a('Подтвердить',
                                                ['tasks/choose', 'taskId' => $task->id, 'executorId' => $respond->executor_id],
                                                ['class' => "button__small-color response-button button", 'type' => "button"]) ?>
                                            <?= Html::a('Отказать',
                                            ['tasks/decline', 'respondId' => $respond->id],
                                            ['class' => 'button__small-color refusal-button button', 'type' => "button"]) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
        <section class="connect-desk">
            <div class="connect-desk__profile-mini">
                <div class="profile-mini__wrapper">
                    <h3>Заказчик</h3>
                    <div class="profile-mini__top">
                        <img src="<?= $task->author->avatar->path ?>" width="62" height="62" alt="Аватар заказчика">
                        <div class="profile-mini__name five-stars__rate">
                            <p><?= $task->author->name ?></p>
                        </div>
                    </div>
                    <p class="info-customer"><span>
                             <?= PluralHelper::Plural(['заданий', 'задание', 'задание', 'задания', 'заданий', 'задания'],
                                 $task->author->AuthorCountTasks) ?>
                        </span><span class="last-">
                            <?= mb_substr(Yii::$app->formatter->asRelativeTime($task->author->dt_add), 0, -6, 'UTF-8') ?> на сайте
                        </span></p>
                    <?= Html::a('Cмотреть профиль', ['users/view', 'id' => $task->author_id], ['class' => 'link-regular']) ?>
                </div>
            </div>
            <?php if($task->executor_id === $user_id || $task->executor_id && $task->author_id === $user_id): ?>
            <div id="chat-container">
                <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
                <chat class="connect-desk__chat" task="<?= $task->id ?>" sender="<?= $user_id ?>" recepient="<?= $user_id === $task->author_id ? $task->executor_id : $task->author_id ?>"></chat>
            </div>
            <?php endif; ?>
        </section>
    </div>
</main>

    <?php if (!$respondAuthor): ?>
<section class="modal response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['tasks/view', 'id' => $task->id],
            'fieldConfig' => [
                'template' => "<p>{label}{input}\n{error}</p>"
            ]
        ])
        ?>
        <?= $form->field($model, 'budget')
            ->textInput(['class' => 'response-form-payment input input-middle input-money', 'style' => 'width: 200px;'])
            ->label('Ваша цена', ['class' => 'form-modal-description'])
        ?>
        <?= $form->field($model, 'content')
            ->textarea(['class' => 'input textarea', 'rows' => 4, 'placeholder' => 'Place your text'])
            ->label('Комментарий', ['class' => 'form-modal-description'])
        ?>
        <?= Html::submitButton('Отправить', ['class' => 'button modal-button']) ?>
        <?php ActiveForm::end() ?>
        <button class="form-modal-close" type="button">Закрыть</button>
</section>
    <?php endif; ?>



<section class="modal completion-form form-modal" id="request-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <?php $reviewForm = ActiveForm::begin([
        'method' => 'post',
        'action' => ['tasks/done', 'taskId' => $task->id, 'status' => $task->status]
    ]) ?>

    <?php $task->status = Tasks::STATUS_DONE ?>
    <?= $reviewForm->field($task, 'status', ['options' => ['tag' => false]])
        ->radioList([Tasks::STATUS_DONE => 'Да', Tasks::STATUS_FAILED => 'Возникли проблемы?'],
            [
                'item' => function ($index, $label, $name) {
                    $class = ['yes', 'difficult'];
                    $_value = [Tasks::STATUS_DONE, Tasks::STATUS_FAILED];
                    return "<input class=\"visually-hidden completion-input completion-input--{$class[$index]}\"
                     id='{$index}' type='radio' name='{$name}' value='{$_value[$index]}' ' >
                     <label class=\"completion-label completion-label--{$class[$index]}\" for='{$index}'>{$label}</label>";
                }
            ])->label(false) ?>

    <?= $reviewForm->field($review, 'content', ['template' => "<p>{label}{input}</p>"])->textarea(['class' => 'input textarea',
        'rows' => 4, 'placeholder' => 'Place your text'])
        ->label('Комментарий', ['class' => 'form-modal-description']) ?>
    <p class="form-modal-description">
        Оценка
    <div class="feedback-card__top--name completion-form-star">
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
    </div>
    </p>
    <?= $reviewForm->field($review, 'ratio')->input('hidden',
        ['id' => 'ratio'])->label(false) ?>
    <?= Html::button('Отправить', ['class' => 'button modal-button', 'type' => 'submit']) ?>
    <?php ActiveForm::end() ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>

<section class="modal form-modal refusal-form" id="refusal-form">
    <h2>Отказ от задания</h2>
    <p>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>
    <?= Html::a('Отказаться', ['tasks/refuse', 'taskId' => $task->id, 'executorId' => $task->executor_id],
        ['class' => 'button__form-modal refusal-button button', 'type' => 'button']) ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>

<section class="modal form-modal refusal-form" id="action_cancel-form">
    <h2>Отказ от задания</h2>
    <p>
        Вы собираетесь отменить это задание.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>
    <?= Html::a('Отказаться', ['tasks/cancel', 'taskId' => $task->id],
        ['class' => 'button__form-modal refusal-button button', 'type' => 'button']) ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>
</div>
<div class="overlay"></div>

