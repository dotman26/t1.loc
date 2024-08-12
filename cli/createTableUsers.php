<?php

use services\Db;

spl_autoload_register(function (string $className) {
    require_once __DIR__ . '/../' . $className . '.php';
});

$db = Db::getInstance();

$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `name` varchar(50) NOT NULL,
    `email` varchar(50) NOT NULL,
    `auth_token` varchar(100),
    `created_at` timestamp,
    `password` varchar(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`name`), -- Наверное, оно должно быть уникальным
    UNIQUE (`email`))
    CHARACTER SET utf8 COLLATE utf8_general_ci
SQL;

$res = $db->query($sql);

if ($res !== null) {
    $users = (require __DIR__ . '/../config/users.php');

    $sql = <<<SQL
        INSERT INTO users (name, email, created_at, password) VALUES (:name, :email, :created_at, :password)
        ON DUPLICATE KEY UPDATE name = VALUES(name), email = VALUES(email), created_at = VALUES(created_at), password = VALUES(password);
    SQL;

    foreach ($users as $user) {
        $res = $db->query($sql, $user);
    }     
}