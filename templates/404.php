<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $cat) : ?>
            <li class="nav__item">
                <a href="pages/<?= $cat['tag'] ?>"><?= $cat['name'] ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<main>
    <section class="lot-item container">
        <h2>404 Страница не найдена</h2>
        <p>Данной страницы не существует на сайте.</p>
    </section>
</main>