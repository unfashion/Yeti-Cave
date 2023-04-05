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
    <form class="form container <?= in_array(!null, $errors) ? 'form--invalid' : '' ?>" method="post" autocomplete="off"> <!-- form
    --invalid -->
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item <?= $errors['email'] ? 'form__item--invalid' : '' ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value = "<?= $_POST['email'] ?? '' ?>">
            <?php if ($errors['email']) : ?><span class="form__error"><?= $errors['email'] ?></span><?php endif ?>
        </div>
        <div class="form__item <?= $errors['password'] ? 'form__item--invalid' : '' ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" value = "<?= $_POST['password'] ?? '' ?>">
            <?php if ($errors['password']) : ?><span class="form__error"><?= $errors['password'] ?></span><?php endif ?>
        </div>
        <div class="form__item <?= $errors['name'] ? 'form__item--invalid' : '' ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите имя" value = "<?= $_POST['name'] ?? '' ?>">
            <?php if ($errors['name']) : ?><span class="form__error"><?= $errors['name'] ?></span><?php endif ?>
        </div>
        <div class="form__item <?= $errors['message'] ? 'form__item--invalid' : '' ?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите как с вами связаться" value = "<?= $_POST['message'] ?? '' ?>"></textarea>
            <?php if ($errors['message']) : ?><span class="form__error"><?= $errors['message'] ?></span><?php endif ?>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
</main>