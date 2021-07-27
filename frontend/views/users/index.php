<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use frontend\controllers\UsersController;

$this->title = 'Задания';
?>

<main class="page-main">
    <div class="main-container page-container">
        <section class="user__search">
            <?php foreach ($users as $user): ?>
                <?php $count = 0;
                      $stars = UsersController::getStars($user->id)?>
            <div class="content-view__feedback-card user__search-wrapper">
                <div class="feedback-card__top">
                    <div class="user__search-icon">
                        <a href="user.html"><img src="./img/man-glasses.jpg" width="65" height="65"></a>
                        <span><?= Yii::$app->i18n->format(
                            '{n, plural, =0{0 заданий} =1{1 задание}
                                    one{# задание} few{# задания} many{# заданий} other{# задания}}',
                                    ['n' => count($user->tasks0)],
                            'ru_RU')  ?></span>
                        <?php foreach ($user->tasks0 as $task) {
                            $count = $count + count ($task->reviews);
                        } ?>
                        <span><?= Yii::$app->i18n->format(
                            '{n, plural, =0{0 отзывов} =1{1 отзыв} one{# отзыв}
                                     few{# отзыва} many{# отзывов} other{# отзыва}}',
                                     ['n' => $count],
                            'ru_RU') ?> </span>
                    </div>
                    <div class="feedback-card__top--name user__search-card">
                        <p class="link-name"><a href="user.html" class="link-regular"><?= Html::encode($user->name) ?></a></p>
                        <span <?= $stars < 1 ? 'class="star-disabled"':'' ?>></span>
                        <span <?=$stars < 2 ? 'class="star-disabled"':'' ?>></span>
                        <span <?=$stars < 3 ? 'class="star-disabled"':'' ?>></span>
                        <span <?=$stars < 4 ? 'class="star-disabled"':'' ?>></span>
                        <span <?=$stars < 5 ? 'class="star-disabled"':'' ?>></span>
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
