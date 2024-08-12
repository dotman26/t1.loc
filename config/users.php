<?php

    /*
        ■ id — первичный ключ, автоинкремент;
        ■ name — строка, имя пользователя;
        ■ email — строка, уникальное поле, email пользователя;
        ■ created_at — дата и время создания записи;
        ■ password — пароль пользователя.
    */
    /*
    password_verify('password', 'hash');
    */

return [
    [
        'name' => 'admin',
        'email' => 'admin@mail.ru',
        'created_at' => date('Y-m-d H:i:s'),
        'password' => password_hash('admin', PASSWORD_DEFAULT),
    ],
    [
        'name' => 'user_1',
        'email' => 'user_1@mail.ru',
        'created_at' => date('Y-m-d H:i:s'),
        'password' => password_hash('user_1', PASSWORD_DEFAULT),
    ],
    [
        'name' => 'user_2',
        'email' => 'user_2@mail.ru',
        'created_at' => date('Y-m-d H:i:s'),
        'password' => password_hash('user_2', PASSWORD_DEFAULT),
    ],
    [
        'name' => 'user_3',
        'email' => 'user_3@mail.ru',
        'created_at' => date('Y-m-d H:i:s'),
        'password' => password_hash('user_3', PASSWORD_DEFAULT),
    ],
];