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