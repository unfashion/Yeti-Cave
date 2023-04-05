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
    <form class="form form--add-lot container <?= in_array(!null, $errors) ? 'form--invalid' : '' ?>" action="/add.php" method="post" enctype="multipart/form-data">
        <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?= $errors['lot_name'] ? 'form__item--invalid' : '' ?>">
                <!-- form__item--invalid -->
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot_name" placeholder="Введите наименование лота" value = "<?= $_POST['lot_name'] ?? '' ?>">
                <?php if ($errors['lot_name']) : ?><span class="form__error"><?= $errors['lot_name'] ?></span><?php endif ?>
            </div>
            <div class="form__item <?= $errors['category_id'] ? 'form__item--invalid' : '' ?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category_id">
                    <?php if(!isset($_POST['category_id'])): ?><option selected="true" disabled="disabled">Выберите категорию</option><?php endif ?>
                    <?php foreach ($categories as $cat) : ?>
                        <li class="nav__item">
                            <option <?php if(isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']): ?> selected="true" <?php endif ?>value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                        </li>
                    <?php endforeach ?>
                </select>
                <?php if ($errors['category_id']) : ?><span class="form__error"><?= $errors['category_id'] ?></span><?php endif ?>
            </div>
        </div>
        <div class="form__item form__item--wide <?= $errors['message'] ? 'form__item--invalid' : '' ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите описание лота"><?= $_POST['message'] ?? '' ?></textarea>
            <?php if ($errors['message']) : ?><span class="form__error"><?= $errors['message'] ?></span><?php endif ?>
        </div>
        <div class="form__item form__item--file  <?= $errors['lot_img'] ? 'form__item--invalid' : '' ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" name="lot_img" value="">
                <label for="lot-img">
                    Добавить
                </label>
                <?php if ($errors['lot_img']) : ?><span class="form__error"><?= $errors['lot_img'] ?></span><?php endif ?>
            </div>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?= $errors['lot_rate'] ? 'form__item--invalid' : '' ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot_rate" placeholder="0" value = "<?= $_POST['lot_rate'] ?? '' ?>">
                <?php if ($errors['lot_rate']) : ?><span class="form__error"><?= $errors['lot_rate'] ?></span><?php endif ?>
            </div>
            <div class="form__item form__item--small <?= $errors['lot_step'] ? 'form__item--invalid' : '' ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot_step" placeholder="0" value = "<?= $_POST['lot_step'] ?? '' ?>">
                <?php if ($errors['lot_step']) : ?><span class="form__error"><?= $errors['lot_step'] ?></span><?php endif ?>
            </div>
            <div class="form__item <?= $errors['lot_date'] ? 'form__item--invalid' : '' ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="lot_date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value = "<?= $_POST['lot_date'] ?? '' ?>">
                <?php if ($errors['lot_date']) : ?><span class="form__error"><?= $errors['lot_date'] ?></span><?php endif ?>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>