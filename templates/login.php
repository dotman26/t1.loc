
<div style="text-align: center;">
    <h1>Авторизация</h1>
    <?php if (!empty($error)): ?>
        <div style="background-color: red;padding: 5px;margin: 15px"><?= $error ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div style="background-color: green;padding: 5px;margin: 15px"><?= $success ?></div>
    <?php endif; ?>
    <?php if (empty($authUser)): ?>
    <form id="login" action="/user/login" method="post">
        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="<?= $_POST['name'] ?? '' ?>">
        <br>
        <label for="password">Пароль</label>
        <input id="password" type="password" name="password" value="<?= $_POST['password'] ?? '' ?>">
        <br>
        <input type="submit" value="Войти">
    </form>
    <?php else: ?>
    <p>Вы вошли, как <?= $authUser->name ?></p>
    <a class="logout" href="/user/logout">Выход</a>
    <?php endif; ?>
</div>