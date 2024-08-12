<div>
    <h1>Список всех пользователей</h1>
    <?php if (!empty($authUser) && $authUser->id == 1): ?>
        <a class="create" href="/user/create">Создать пользователя</a>
    <?php endif; ?>

    <div class="users">
        <?php foreach ($users as $user): ?>
            <div data-id="<?= $user->id ?>" class="user">
                <h2><?= $user->name ?></h2>
                <p><?= $user->email ?></p>
                <a class="view" href="/user/<?= $user->id ?>">Показать</a>

                <?php if (!empty($authUser) && $authUser->id == 1): ?>
                    <a class="edit" href="/user/edit/<?= $user->id ?>">Редактировать</a>
                    <a class="delete" href="/user/delete/<?= $user->id ?>">Удалить</a>
                <?php endif; ?>

                <hr>
            </div>
        <?php endforeach; ?>
    </div>
</div>