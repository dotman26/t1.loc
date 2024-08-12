<h1>Создание нового пользователя</h1>
<?php if(count($user->errors) > 0): ?>
    <div style="background-color: red;padding: 5px;margin: 15px">
    <?php foreach ($user->errors as $attribute => $errors): ?>
        <?php foreach ($errors as $error): ?>
            <?= $error . '<br>' ?>
        <? endforeach; ?>
    <? endforeach; ?>
    </div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div style="background-color: green;padding: 5px;margin: 15px"><?= $success ?></div>
<?php endif; ?>
<form id="create" action="/user/create" method="post">
    <label for="name">name</label>
    <br>
    <input type="text" name="name" id="name" value="<?= $_POST['name'] ?? '' ?>" size="50">
    <br>
    <label for="email">email</label><br>
    <input type="text" name="email" id="email" value="<?= $_POST['email'] ?? '' ?>" size="50">
    <br>
    <label for="password">password</label><br>
    <input type="text" name="password" id="email" value="<?= $_POST['password'] ?? '' ?>" size="50">
    <br>
    <input type="submit" value="Создать">
 </form>