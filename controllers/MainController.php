<?php

namespace controllers;

use view\View;
use models\Users;
use services\Auth;

class MainController
{
    private $view;

    private $user;

    public function __construct()
    {
        $this->user = Auth::getUserByToken();
        $this->view = new View(__DIR__ . '/../templates');
        $this->view->setVar('authUser', $this->user);
    }

    public function main()
    {
        $users = Users::findAll();

        return $this->view->render('main.php', ['users' => $users], $_GET['ajax'] ?? false);
    }
}