<?php

namespace models;

use services\Db;
use models\BaseModel;

class Users extends BaseModel
{
    protected $name;

    protected $email;

    protected $createdAt;

    protected $password;

    protected static function getTableName(): string 
    {
        return 'users';
    }

    public function rules(): Array
    {
        return [
            [['name', 'email', 'password'], 'required'],
            [['name', 'email'], 'unique'],
            ['email', 'email'],
            ['name', 'match', '/^\w+$/'],
            [['name', 'email'], 'string', 5, 50],
            ['password', 'string', 5, 100]
        ];
    }

    public function loadFromArray(array $fields): Users
    {
        $this->name = $fields['name'] ?? '';
        $this->email = $fields['email'] ?? '';
        $this->password = $fields['password'];
        $this->createdAt = date('Y-m-d H:i:s');

        return $this;
    }
}