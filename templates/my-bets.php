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
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php if ($bets) : ?>
                <?php foreach ($bets as $bet) :
                    $status = '';
                    $time_left = time_left($bet['lot_end_datetime']);
                    if ($bet['winner_id'] == $user_id and $bet['lot_price'] == $bet['bet_price']) $status = 'win';
                    else if ($time_left['is_end']) $status = 'end';
                    else if ($time_left['is_fire']) $status = 'finishing';
                ?>
                    <tr class="rates__item<?= $status ? " rates__item--$status" : "" ?>">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src="<?= $bet['lot_img'] ?>" width="54" height="40" alt="Сноуборд">
                            </div>
                            <h3 class="rates__title"><a href="lot.php?id=<?= $bet['lot_id'] ?>"><?= $bet['lot_name'] ?></a></h3>
                        </td>
                        <td class="rates__category">
                            <?= $bet['category_name'] ?>
                        </td>
                        <td class="rates__timer">
                            <div class="timer<?= $status ? " timer--$status" : "" ?>">
                                <?= $status == 'win' ? 'Ставка выиграла' : ($status == 'end' ? 'Торги окончены' : $time_left['hours'] . ':' . $time_left['minutes']) ?>
                            </div>
                        </td>
                        <td class="rates__price">
                            <?= price_format($bet['bet_price']) ?>
                        </td>
                        <td class="rates__time">
                            <?= bet_time_format($bet['bet_create_datetime'])  ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </table>
    </section>
</main>