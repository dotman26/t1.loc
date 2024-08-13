<?php

namespace controllers;

use view\View;
use services\Auth;
use models\Users;
use models\Login;
use exceptions\NotFoundException;
use exceptions\InvalidArgumentException;
use exceptions\UnauthorizedException;

class UserController
{
    private $view;

    private $db;

    private $user;

    public function __construct()
    {
        $this->user = Auth::getUserByToken();
        $this->view = new View(__DIR__ . '/../templates');
        $this->view->setVar('authUser', $this->user);
    }

    public function view(int $userId): ?string
    {
        $user = Users::findById($userId);

        if ($user === null) {
            throw new NotFoundException('Пользователь с таким ID не существует');
        }
        
        return $this->view->render('user.php', ['user' => $user], $_GET['ajax'] ?? false);
    }

    public function create(): ?string
    {
        if ($this->user === null) {
            throw new UnauthorizedException('Необходимо авторизоваться');
        }

        if ($this->user->id != 1) {
            throw new UnauthorizedException('Вы не вявляетесь администратором');
        }

        $user = new Users();

        if (!empty($_POST)) {
            $user->loadFromArray($_POST);

            if ($user->validate()) {
                $user->password = password_hash($user->password, PASSWORD_DEFAULT);

                $user->save(['name', 'email', 'createdAt', 'password']);

                $this->view->setVar('success', 'Пользователь ID: ' . $user->id . ' создан');

                if (! isset($_GET['ajax']) || $_GET['ajax'] != '1') {
                    header('Location: ./' . $user->id);
                    exit;
                }
            }
        }
        
        return $this->view->render('create.php', ['user' => $user], $_GET['ajax'] ?? false);
    }

    public function edit(int $userId): ?string
    {
        if ($this->user === null) {
            throw new UnauthorizedException('Необходимо авторизоваться');
        }

        if ($this->user->id != 1) {
            throw new UnauthorizedException('Вы не вявляетесь администратором');
        }

        $user = Users::findById($userId);

        if ($user === null) {
            throw new NotFoundException('Пользователь с таким ID не существует');
        }

        $user->password = null;

        if (!empty($_POST)) {
            $user->loadFromArray($_POST);

            $attributes = ['name', 'email', 'createdAt'];

            if ($user->validate(['password' => ['required', 'string']])) {
                if ($user->password !== '') {
                    $user->password = password_hash($user->password, PASSWORD_DEFAULT);
                    $attributes[] = 'password';
                }

                $user->save($attributes);

                $user->password = '';

                $this->view->setVar('success', 'Пользователь ID: ' . $user->id . ' изменен');

                if (! isset($_GET['ajax']) || $_GET['ajax'] != '1') {
                    header('Location: ./' . $user->id);
                    exit;
                }
            }
        }

        return $this->view->render('update.php', ['user' => $user], $_GET['ajax'] ?? false);
    }

    public function delete(int $userId): ?string
    {
        if ($this->user === null) {
            throw new UnauthorizedException('Необходимо авторизоваться');
        }

        if ($this->user->id != 1) {
            throw new UnauthorizedException('Вы не вявляетесь администратором');
        }

        if ($userId == 1) {
            throw new UnauthorizedException('Нельзя удалить администратора');
        }

        $user = Users::findById($userId);

        if ($user === null) {
            throw new NotFoundException('Пользователь с таким ID не существует');
        }

        $user->delete();

        $this->view->setVar('success', 'Пользователь ID: ' . $user->id . ' удален');
    }

    public function login(): ?string
    {
        if (!empty($_POST)) {
            try {
                $user = Login::login($_POST);

                Auth::createToken($user);

                $url = (! isset($_GET['ajax']) || $_GET['ajax'] != '1') ? '/' : '/?ajax=1';

                //if (! isset($_GET['ajax']) || $_GET['ajax'] != '1') {
                    header('Location: ' . $url );
                    exit;
                //}
            } catch (InvalidArgumentException $e) {
                return $this->view->render('login.php', ['error' => $e->getMessage()], $_GET['ajax'] ?? false);
            }
        }
        
        return $this->view->render('login.php', [], $_GET['ajax'] ?? false);
    }

    public function logout(): ?string
    {
        try {
            $user = Login::logout($this->user);

            Auth::deleteToken();

            $url = '/user/login' . (! isset($_GET['ajax']) || $_GET['ajax'] != '1' ? '' : '?ajax=1');

            //if (! isset($_GET['ajax']) || $_GET['ajax'] != '1') {
                header('Location: ' . $url);
                exit;
            //}
            $this->view->setVar('authUser', null);
        } catch (InvalidArgumentException $e) {
            return $this->view->render('login.php', ['error' => $e->getMessage()], $_GET['ajax'] ?? false);
        }

        return $this->view->render('login.php', [], $_GET['ajax'] ?? false);
    }
}