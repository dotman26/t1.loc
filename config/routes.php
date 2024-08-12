<?php

return [
    '|^$|' => [\controllers\MainController::class, 'main'],
    '|^user/(\d+)$|' => [\controllers\UserController::class, 'view'],
    '|^user/create$|' => [\controllers\UserController::class, 'create'],
    '|^user/edit/(\d+)$|' => [\controllers\UserController::class, 'edit'],
    '|^user/delete/(\d+)$|' => [\controllers\UserController::class, 'delete'],
    '|^user/login$|' => [\controllers\UserController::class, 'login'],
    '|^user/logout$|' => [\controllers\UserController::class, 'logout'],
];