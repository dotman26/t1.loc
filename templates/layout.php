<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тест</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>

<table class="layout">
    <tr>
        <td class="header">
            Таблица пользователей
        </td>
        <td>
            <?= !empty($authUser) ? 'Привет, ' . $authUser->name . ' <a class="logout" href="/user/logout">Выход</a>': '<a class="login" href="/user/login">Вход</a>' ?>
        </td>
    </tr>
    <tr>
        <td id="main">
            <?php include __DIR__ . '/' . $templateName; ?>
        </td>

        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a class="home" href="/">Главная страница</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="footer" colspan="2">Тестовое задание</td>
    </tr>
</table>
<script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>
    <script src="/assets/script.js"></script>
</body>
</html>