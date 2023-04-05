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
    <div class="container">
        <section class="lots">

            <h2>
                    Все лоты в категории «<?= $category['name'] ?>»
            </h2>
            <?php if ($lots) : ?>
                <ul class="lots__list">
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
                                    <?php $time_left = time_left($lot['end_datetime']) ?>
                                    <div class="lot__timer timer <?php if ($time_left['is_fire']) : ?>timer--finishing<?php endif ?>">
                                        <?= $time_left['hours'] . ':' . $time_left['minutes'] ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>

        </section>
        <?php if ($page_count > 1):
            $id = $category['id'];
            $prev_page = $page - 1;
            $next_page = $page + 1
        ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev"><a <?= $page > 1 ? "href=/category.php?id=$id&page=$prev_page" : '' ?>>Назад</a></li>
                <?php for ($i = 1; $i <= $page_count; $i++) : ?>
                    <li class="pagination-item <?= $i == $page ? 'pagination-item-active' : '' ?>"><a href="/category.php?id=<?= $id ?>&page=<?= $i ?>"><?= $i ?></a></li>
                <?php endfor ?>
                <li class="pagination-item pagination-item-next"><a <?= $page < $page_count ? "href=/category.php?id=$id&page=$next_page" : '' ?>>Вперед</a></li>
            </ul>
        <?php endif ?>
    </div>
</main>