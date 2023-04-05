<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $cat) : ?>
                <li class="nav__item">
                    <a href="/category.php?id=<?= $cat['id'] ?>"><?= $cat['name'] ?></a>
                </li>
            <?php endforeach ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2><?= $lot['name'] ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="../<?= $lot['img'] ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['category_name'] ?></span></p>
                <p class="lot-item__description"><?= $lot['description'] ?></p>
            </div>
            <div class="lot-item__right">

                <div class="lot-item__state">

                    <?php $time_left = time_left($lot['end_datetime']) ?>
                    <div class="lot-item__timer timer <?php if ($time_left['is_fire']) : ?>timer--finishing<?php endif ?>">
                        <?= $time_left['hours'] . ':' . $time_left['minutes'] ?>
                    </div>

                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= price_format($lot['price']) ?><b class="rub">р</b></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= price_format($min_bet) ?></span>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['id']) and $_SESSION['id'] != $lot['author_id'] ) : ?>
                        <form class="lot-item__form" method="post" autocomplete="off">
                            <p class="lot-item__form-item form__item <?= $bet_error ? 'form__item--invalid' : '' ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost" placeholder="<?= $min_bet ?>">
                                <?php if ($bet_error) : ?><span class="form__error"><?= $bet_error ?></span><?php endif ?>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    <?php endif ?>
                </div>
                <?php if ($bets) : ?>
                    <div class="history">
                        <h3>История ставок (<span><?= count($bets) ?></span>)</h3>
                        <table class="history__list">
                            <?php foreach ($bets as $bet) : ?>
                                <tr class="history__item">
                                    <td class="history__name"> <?= $bet['author_name'] ?></td>
                                    <td class="history__price"><?= price_format($bet['price']) ?> р</td>
                                    <td class="history__time"><?= bet_time_format($bet['create_datetime']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </table>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </section>
</main>