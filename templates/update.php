<h1>Редактирование пользователя</h1>
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

<form id="update" action="/user/edit/<?= $user->id ?>" method="post">
    <label for="name">name</label>
    <br>
    <input type="text" name="name" id="name" value="<?= $user->name ?? '' ?>" size="50">
    <br>
    <label for="email">email</label><br>
    <input type="text" name="email" id="email" value="<?= $user->email ?? '' ?>" size="50">
    <br>
    <label for="password">password</label><br>
    <input type="text" name="password" id="password" value="<?= $user->password ?? '' ?>" size="50">
    <br>
    <input type="submit" value="Изменить">
 </form>