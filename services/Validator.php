<?php

namespace services;

use models\BaseModel;
use exceptions\InvalidConfigException;

class Validator
{
    protected $model;

    protected $type;

    protected $attributes;

    protected $rules;

    protected $skip;

    private function __construct($model, $type, $attributes, $rules, $skip)
    {
        $this->model = $model;
        $this->type = $type;
        $this->attributes = $attributes;
        $this->rules = $rules;
        $this->skip = $skip;
    }

    public function validate()
    {
        switch ($this->type) {
            case 'required':
                $this->validateRequired();
                break;

            case 'unique':
                $this->validateUnique();
                break;

            case 'email':
                $this->validateEmail();
                break;

            case 'match':
                $this->validateMatch();
                break;

            case 'string':
                $this->validateString();
                break;
            default:
                throw new InvalidConfigException('Неизвестный валидатор');
        }
    }

    public static function createValidator(BaseModel $model, $rule, $skip = false)
    {
        return new Validator($model, $rule[1], (array) $rule[0], array_slice($rule, 2), $skip);
    }

    public function validateRequired()
    {
        foreach ($this->attributes as $attribute) {
            $data = trim(stripslashes(htmlspecialchars((string) $this->model->$attribute)));

            if ($data === '') {
                $this->model->addError($attribute, 'Атрибут ' . $attribute . ' обязателен к заполнению');
            }
        }
    }

    public function validateUnique()
    {
        foreach ($this->attributes as $attribute) {
            $row = $this->model::findOneByColumn($attribute, $this->model->$attribute, $this->model->id);

            if ($row) {
                $this->model->addError($attribute, 'Атрибут ' . $attribute . ' должен быть уникальным');
            }
        }
    }

    public function validateEmail()
    {
        foreach ($this->attributes as $attribute) {
            if (!filter_var($this->model->$attribute, FILTER_VALIDATE_EMAIL)) {
                $this->model->addError($attribute, 'Атрибут ' . $attribute . ' не является валидным email адресом');
            }
        }
    }

    public function validateMatch()
    {
        foreach ($this->attributes as $attribute) {
            if (empty($this->rules[0]))
                throw new InvalidConfigException('Неверная конфигурация match у атрибута ' . $attribute);

            if (!preg_match((string) $this->rules[0], $this->model->$attribute)) {
                $this->model->addError($attribute, 'Атрибут ' . $attribute . ' имеет недопустимое значение');
            }
        }
    }

    public function validateString()
    {
        foreach ($this->attributes as $attribute) {
            $value = $this->model->$attribute;

            if (!is_string($value)) {
                $value = (string)$value;
            }

            $length = mb_strlen($value, 'UTF-8');

            if (!empty($this->rules[0]) && $length < (int) $this->rules[0]) {
                $this->model->addError($attribute, 'Атрибут ' . $attribute . ' должен содержать минимум ' . (int) $this->rules[0] . ' символов');
            }

            if (!empty($this->rules[1]) && $length > (int) $this->rules[1]) {
                $this->model->addError($attribute, 'Атрибут ' . $attribute . ' должен содержать максимум ' . (int) $this->rules[1] . ' символов');
            }
        }
    }
}