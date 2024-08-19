<?php

namespace models;

use services\Db;
use models\Users;
use exceptions\InvalidArgumentException;

class Login extends Users
{
    public function rules(): Array
    {
        return [
            [['name', 'password'], 'required'],
            ['name', 'match', '/^\w+$/'],
            ['name', 'string', 5, 50],
            ['password', 'string', 5, 100]
        ];
    }

    public static function login(array $data): Users
    {
        $user = new Login();
        $user->name = $data['name'];
        $user->password = $data['password'];
        
        if (!$user->validate()) {
            throw new InvalidArgumentException('Ошибка валидации данных');
        }

        $user = Login::findOneByColumn('name', $data['name']);

        if ($user === null) {
            throw new InvalidArgumentException('Нет пользователя с таким именем');
        }

        if (!password_verify($data['password'], $user->password)) {
            throw new InvalidArgumentException('Неверный пароль');
        }

        $user->refreshAuthToken();

        $user->save(['authToken']);

        return $user;
    }

    public static function logout(?Users $user)
    {
        if ($user ===  null) {
            throw new InvalidArgumentException('Вы не авторизованы');
        }

        $user->authToken = '';

        $user->save(['authToken']);
    }

    public function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }
}