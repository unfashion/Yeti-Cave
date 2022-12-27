<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach ($categories as $cat) : ?>
                <li class="promo__item promo__item--<?= $cat['tag'] ?>">
                    <a class="promo__link" href="pages/<?= $cat['tag'] ?>"><?= $cat['name'] ?></a>
                </li>
            <?php endforeach ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
            <?php foreach ($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $lot['img'] ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $lot['category'] ?></span>
                        <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= $lot['id'] ?>"><?= htmlspecialchars($lot['name']) ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= price_format($lot['price']) ?></span>
                            </div>
                            <div class="lot__timer timer <?php if (time_left($lot['end_datetime'])[0] < 1) : ?>timer--finishing<?php endif ?>">
                                <?= time_left($lot['end_datetime'])[0] . ':' . time_left($lot['end_datetime'])[1] ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    </section>
</main>