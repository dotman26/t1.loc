<div data-id="<?= $user->id ?>" class="user">
    <h2>Пользователь <?= $user->name ?></h2>
    <p>Имя: <?= $user->name ?></p>
    <p>Почта: <?= $user->email ?></p>
    <p>Создан: <?= $user->createdAt ?></p>
    <?= !empty($authUser) ? '<a class="edit" href="/user/edit/' . $user->id . '">Редактировать</a>' : '' ?>
    <hr>
</div>
