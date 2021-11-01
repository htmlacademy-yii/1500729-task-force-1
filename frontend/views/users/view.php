<?php
/* @var $this yii\web\View */
/* @var $user \frontend\models\Users */
/* @var $reviews \frontend\models\Reviews */

use taskforce\app\RatioWidget;
use taskforce\app\StarsWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use taskforce\helpers\PluralHelper;

$stars = round($user->calculateStars($user->id),2);
?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="content-view">
            <div class="user__card-wrapper">
                <div class="user__card">
                    <img src="/img/man-hat.png" width="120" height="120" alt="Аватар пользователя">
                    <div class="content-view__headline">
                        <h1><?= Html::encode($user->name) ?></h1>
                        <p>Россия, <?= $user->location->location ?>, <?= mb_substr(Yii::$app->formatter->asRelativeTime($user->birthday), 0, -6, 'UTF-8')?></p>
                        <div class="profile-mini__name five-stars__rate">
                            <?= StarsWidget::widget(['stars' => $stars]) ?>
                            <b><?= $stars ?></b>
                        </div>
                        <b class="done-task">Выполнил <?= PluralHelper::Plural(['заказов', 'заказ', 'заказ', 'заказа', 'заказов', 'заказа'],
                                $user->done_tasks) ?></b><b class="done-review">Получил <?= PluralHelper::Plural(['отзывов', 'отзыв', 'отзыв', 'отзыва', 'отзывов', 'отзыва'],
                                count($reviews))  ?></b>
                    </div>
                    <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                        <span>Был на сайте <?= Yii::$app->formatter->format($user->dt_last_activity, 'relativeTime')?></span>
                        <a href="#"><b></b></a>
                    </div>
                </div>
                <div class="content-view__description">
                    <p><?= Html::encode($user->information) ?></p>
                </div>
                <div class="user__card-general-information">
                    <div class="user__card-info">
                        <h3 class="content-view__h3">Специализации</h3>
                        <div class="link-specialization">
                            <?php foreach ($user->executorCategories as $category): ?>
                                <?= Html::a(Html::encode($category->category->title),['tasks/index', 'category_id' => $category->category->id], ['class' => 'link-regular'] )?>
                            <?php endforeach; ?>
                        </div>
                        <h3 class="content-view__h3">Контакты</h3>
                        <div class="user__card-link">
                            <?= Html::a(Html::encode($user->phone),'tel:'.$user->phone, ['class' => 'user__card-link--tel link-regular']) ?>
                            <?= Html::mailto($user->email, $user->email, ['class' => "user__card-link--email link-regular"])?>
                            <?= Html::a(Html::encode($user->skype), 'skype:'.$user->skype.'?call', ['class' => 'user__card-link--skype link-regular']) ?>
                        </div>
                    </div>
                    <div class="user__card-photo">
                        <h3 class="content-view__h3">Фото работ</h3>
                        <a href="#"><img src="/img/rome-photo.jpg" width="85" height="86" alt="Фото работы"></a>
                        <a href="#"><img src="/img/smartphone-photo.png" width="85" height="86" alt="Фото работы"></a>
                        <a href="#"><img src="/img/dotonbori-photo.png" width="85" height="86" alt="Фото работы"></a>
                    </div>
                </div>
            </div>
            <?php if($reviews): ?>
            <div class="content-view__feedback">
                <h2>Отзывы<span>(<?= count($reviews) ?>)</span></h2>
                <div class="content-view__feedback-wrapper reviews-wrapper">
                    <?php foreach ($reviews as $review): ?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">Задание <?= Html::a('«'.Html::encode($review->task->title).'»',
                            ['tasks/view', 'id' => $review->task->id],
                            ['class' => 'regular-link'])  ?> </p>
                        <div class="card__review">
                            <a href="#"><img src="/img/man-glasses.jpg" width="55" height="54"></a>
                            <div class="feedback-card__reviews-content">
                                <p class="link-name link"><?= Html::a(Html::encode($review->task->author->name),
                                    ['users/view', 'id' => $review->task->author->id],
                                    ['class' => 'link-regular']) ?>
                                <p class="review-text">
                                    <?= Html::encode($review->content) ?>
                                </p>
                            </div>
                            <div class="card__review-rate">
                                <?= RatioWidget::widget(['ratio' => $review->ratio]) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

            <?php endif; ?>
        </section>
        <section class="connect-desk">
            <div class="connect-desk__chat">
            </div>
        </section>
    </div>
</main>

