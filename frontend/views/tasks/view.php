<?php
/* @var $this yii\web\View */
/* @var $task \frontend\models\Tasks */
/* @var $responds \frontend\models\Responds */
/** @var int $countAuthorTasks */
use frontend\models\Users;
use taskforce\app\RatioWidget;
use taskforce\app\StarsWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use taskforce\helpers\PluralHelper;


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
                                        ['class' => 'link-regular'] )?>
                                <?= Yii::$app->formatter->format($task->dt_add, 'relativeTime')?></span>
                        </div>
                        <b class="new-task__price new-task__price--<?= $task->category->icon ?> content-view-price"><?= Html::encode($task->budget) ?><b> ₽</b></b>
                        <div class="new-task__icon new-task__icon--<?= $task->category->icon ?> content-view-icon"></div>
                    </div>
                    <div class="content-view__description">
                        <h3 class="content-view__h3">Общее описание</h3>
                        <p>
                            <?= Html::encode($task->description) ?>
                        </p>
                    </div>
                    <div class="content-view__attach">
                        <h3 class="content-view__h3">Вложения</h3>
                        <a href="#">my_picture.jpeg</a>
                        <a href="#">agreement.docx</a>
                    </div>
                    <div class="content-view__location">
                        <h3 class="content-view__h3">Расположение</h3>
                        <div class="content-view__location-wrapper">
                            <div class="content-view__map">
                                <a href="#"><img src="/img/map.jpg" width="361" height="292"
                                                 alt="<?= $task->location->location ?>, <?= Html::encode($task->address) ?>"></a>
                            </div>
                            <div class="content-view__address">
                                <span class="address__town"><?= $task->location->location ?></span><br>
                                <span><?= Html::encode($task->address) ?></span>
                                <p>Вход под арку, код домофона 1122</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-view__action-buttons">
                    <button class=" button button__big-color response-button open-modal"
                            type="button" data-for="response-form">Откликнуться
                    </button>
                    <button class="button button__big-color refusal-button open-modal"
                            type="button" data-for="refuse-form">Отказаться
                    </button>
                    <button class="button button__big-color request-button open-modal"
                            type="button" data-for="complete-form">Завершить
                    </button>
                </div>
            </div>
            <?php if ($responds): ?>
            <div class="content-view__feedback">
                <h2>Отклики <span>(<?= count($responds) ?>)</span></h2>
                <div class="content-view__feedback-wrapper">
                    <?php foreach ($responds as $respond): ?>
                    <div class="content-view__feedback-card">
                        <div class="feedback-card__top">
                            <a href="user.html"><img src="/img/man-glasses.jpg" width="55" height="55"></a>
                            <div class="feedback-card__top--name">
                                <p><?= Html::a(Html::encode($respond->executor->name),
                                        ['users/view', 'id' => $respond->executor->id ], ['class' => 'link-regular']) ?></p>
                                <?php $stars = Users::calculateStars($respond->executor->id) ?>
                                <?= StarsWidget::widget(['stars' => $stars]) ?>
                                <b><?= round($stars,2) ?></b>
                            </div>
                            <span class="new-task__time">
                                <?= Yii::$app->formatter->format($respond->dt_add, 'relativeTime')?>
                            </span>
                        </div>
                        <div class="feedback-card__content">
                            <p>
                                <?= Html::encode($respond->content) ?>
                            </p>
                            <span><?= Html::encode($respond->budget) ?> ₽</span>
                        </div>
                        <div class="feedback-card__actions">
                            <a class="button__small-color response-button button"
                               type="button">Подтвердить</a>
                            <a class="button__small-color refusal-button button"
                               type="button">Отказать</a>
                        </div>
                    </div>
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
                        <img src="/img/man-brune.jpg" width="62" height="62" alt="Аватар заказчика">
                        <div class="profile-mini__name five-stars__rate">
                            <p><?= $task->author->name ?></p>
                        </div>
                    </div>
                    <p class="info-customer"><span>
                             <?= PluralHelper::Plural(['заданий', 'задание', 'задание', 'задания', 'заданий', 'задания'],
                                $countAuthorTasks) ?>
                        </span><span class="last-">
                            <?= mb_substr(Yii::$app->formatter->asRelativeTime($task->author->dt_add), 0, -6, 'UTF-8') ?> на сайте
                        </span></p>
                    <?= Html::a('Cмотреть профиль', ['users/view', 'id' => $task->author_id], ['class' => 'link-regular']) ?>
                </div>
            </div>
            <div id="chat-container">
                <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
                <chat class="connect-desk__chat"></chat>
            </div>
        </section>
    </div>
</main>
<section class="modal response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>
    <form action="#" method="post">
        <p>
            <label class="form-modal-description" for="response-payment">Ваша цена</label>
            <input class="response-form-payment input input-middle input-money" type="text" name="response-payment"
                   id="response-payment">
        </p>
        <p>
            <label class="form-modal-description" for="response-comment">Комментарий</label>
            <textarea class="input textarea" rows="4" id="response-comment" name="response-comment"
                      placeholder="Place your text"></textarea>
        </p>
        <button class="button modal-button" type="submit">Отправить</button>
    </form>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>
    <form action="#" method="post">
        <input class="visually-hidden completion-input completion-input--yes" type="radio" id="completion-radio--yes"
               name="completion" value="yes">
        <label class="completion-label completion-label--yes" for="completion-radio--yes">Да</label>
        <input class="visually-hidden completion-input completion-input--difficult" type="radio"
               id="completion-radio--yet" name="completion" value="difficulties">
        <label class="completion-label completion-label--difficult" for="completion-radio--yet">Возникли проблемы</label>
        <p>
            <label class="form-modal-description" for="completion-comment">Комментарий</label>
            <textarea class="input textarea" rows="4" id="completion-comment" name="completion-comment"
                      placeholder="Place your text"></textarea>
        </p>
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
        <input type="hidden" name="rating" id="rating">
        <button class="button modal-button" type="submit">Отправить</button>
    </form>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<section class="modal form-modal refusal-form" id="refuse-form">
    <h2>Отказ от задания</h2>
    <p>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>
    <button class="button__form-modal refusal-button button"
            type="button">Отказаться
    </button>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
</div>
<div class="overlay"></div>
<script src="./js/main.js"></script>
<script src="./js/messenger.js"></script>
