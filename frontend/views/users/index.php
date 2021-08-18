<?php
/* @var $this yii\web\View */
/* @var $users \frontend\models\Users[] */

use taskforce\app\StarsWidget;
use taskforce\helpers\PluralHelper;
use yii\helpers\Html;
use frontend\controllers\UsersController;

$this->title = 'Задания';
?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="user__search">
            <?php foreach ($users as $user): ?>
                <?php $count = 0;
                      $stars = round($user->calculateStars($user->id),2); ?>
            <div class="content-view__feedback-card user__search-wrapper">
                <div class="feedback-card__top">
                    <div class="user__search-icon">
                        <a href="user.html"><img src="./img/man-glasses.jpg" width="65" height="65"></a>
                        <span><?= PluralHelper::Plural(['заданий', 'задание', 'задание', 'задания', 'заданий', 'задания'],
                                                        count($user->executeTasks)) ?></span>
                        <?php foreach ($user->executeTasks as $task) {
                            $count = $count + count ($task->reviews);
                        } ?>
                        <span><?= PluralHelper::Plural(['отзывов', 'отзыв', 'отзыв', 'отзыва', 'отзывов', 'отзыва'],
                                                        $count) ?> </span>
                    </div>
                    <div class="feedback-card__top--name user__search-card">
                        <p class="link-name"><a href="user.html" class="link-regular"><?= Html::encode($user->name) ?></a></p>
                        <?= StarsWidget::widget(['stars' => $stars]) ?>
                        <b><?= $stars ?></b>
                        <p class="user__search-content">
                            <?= Html::encode($user->information)?>
                        </p>
                    </div>
                    <span class="new-task__time">Был на сайте <?= Yii::$app->formatter->format($user->dt_last_activity, 'relativeTime')?></span>
                </div>
                <div class="link-specialization user__search-link--bottom">
                    <?php foreach ($user->executorCategories as $category): ?>
                    <a href="browse.html" class="link-regular"><?= $category->category->title ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
        <section class="search-task">
            <div class="search-task__wrapper">
                <form class="search-task__form" name="users" method="post" action="#">
                    <fieldset class="search-task__categories">
                        <legend>Категории</legend>
                        <label class="checkbox__legend">
                            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked disabled>
                            <span>Курьерские услуги</span>
                        </label>
                        <label class="checkbox__legend">
                            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked>
                            <span>Грузоперевозки</span>
                        </label>
                        <label class="checkbox__legend">
                            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
                            <span>Переводы</span>
                        </label>
                        <label class="checkbox__legend">
                            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
                            <span>Строительство и ремонт</span>
                        </label>
                        <label class="checkbox__legend">
                            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
                            <span>Выгул животных</span>
                        </label>
                    </fieldset>
                    <fieldset class="search-task__categories">
                        <legend>Дополнительно</legend>
                        <label class="checkbox__legend">
                            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
                            <span>Сейчас свободен</span>
                        </label>
                        <label class="checkbox__legend">
                            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
                            <span>Сейчас онлайн</span>
                        </label>
                        <label class="checkbox__legend">
                            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
                            <span>Есть отзывы</span>
                        </label>
                        <label class="checkbox__legend">
                            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
                            <span>В избранном</span>
                        </label>
                    </fieldset>
                    <label class="search-task__name" for="110">Поиск по имени</label>
                    <input class="input-middle input" id="110" type="search" name="q" placeholder="">
                    <button class="button" type="submit">Искать</button>
                </form>
            </div>
        </section>
    </div>
</main>
