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
    <form class="form container <?= in_array(!null, $errors) ? 'form--invalid' : '' ?>" method="post"> <!-- form--invalid -->
        <h2>Вход</h2>
        <?php if ($errors['identify']) : ?><span class="form__error form__error--bottom"><?= $errors['identify'] ?></span><?php endif ?>
        <div class="form__item <?= ($errors['email'] or $errors['identify']) ? 'form__item--invalid' : '' ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail">
            <?php if ($errors['email']) : ?><span class="form__error"><?= $errors['email'] ?></span><?php endif ?>
        </div>
        <div class="form__item <?= ($errors['password'] or $errors['identify']) ? 'form__item--invalid' : '' ?> form__item--last">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">
            <?php if ($errors['password']) : ?><span class="form__error"><?= $errors['password'] ?></span><?php endif ?>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>