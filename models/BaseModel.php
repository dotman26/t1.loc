<?php

namespace models;

use services\Db;
use services\Validator;

abstract class BaseModel
{
    protected $id;

    private $errors = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function __set($name, $value)
    {
        $camelCase = $this->toCamelCase($name);

        $this->$camelCase = $value;
    }

    public function __get($attribute): string|array|null
    {
        if (isset($this->$attribute)) {
            return $this->$attribute;
        }
        return null;
    }

    public static function findById(int $id): ?self
    {
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id LIMIT 1;',
            [':id' => $id],
            static::class
        );

        return $result ? $result[0] : null;
    }

    public static function findOneByColumn(string $column, $value, $id = null): ?self
    {
        $db = Db::getInstance();

        if ($id === null) {
            $params = [
                'SELECT * FROM `' . static::getTableName() . '` WHERE `' . $column . '` = :value LIMIT 1;',
                [':value' => $value]
            ];
        } else {
            $params = [
                'SELECT * FROM `' . static::getTableName() . '` WHERE `' . $column . '` = :value AND id != :id LIMIT 1;',
                [':value' => $value, 'id' => $id]
            ];
        }

        $params[] = static::class;

        $result = $db->query(...$params);

        return $result ? $result[0] : null;
    }

    public static function findAll(): array
    {
        $db = Db::getInstance();

        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
    }

    public function save($attributes = null): void
    {
        $preparedAttributes = $this->prepareAttributes($attributes);

        if (count($preparedAttributes) > 0) {
            if ($this->id !== null) {
                $this->update($preparedAttributes);
            } else {
                $this->insert($preparedAttributes);
            }
        }
    }

    private function update(array $preparedAttributes): void
    {
        $params = array_map(
            fn(string $key): string => '`' . $key . '` = :' . $key,
            array_keys($preparedAttributes));

        $sql = 'UPDATE `' . static::getTableName() . '` SET ' . implode(', ', $params) . ' WHERE `id` = ' . $this->id;

        $db = Db::getInstance();
        $db->query($sql, $preparedAttributes, static::class);
    }

    private function insert(array $preparedAttributes): void
    {
        $keys = array_keys($preparedAttributes);

        $sql = 'INSERT INTO `' . static::getTableName() . '` (`' . implode('`, `', $keys) . '`) VALUES (:' . implode(', :', $keys) . ');';

        $db = Db::getInstance();
        $db->query($sql, $preparedAttributes, static::class);

        $this->id = $db->getLastInsertId();
    }

    public function delete(): void
    {
        $db = Db::getInstance();
        $db->query(
            'DELETE FROM `' . static::getTableName() . '` WHERE id = :id',
            [':id' => $this->id]
        );
        
        $this->id = null;
    }

    abstract protected static function getTableName(): string;

    abstract public function rules(): array;

    private function prepareAttributes($attributes = null): array
    {
        if ($attributes === null || !is_array($attributes)) {
            return [];
        }

        $result = [];

        foreach ($attributes as $attribute) {
            $propertyName = $this->fromCamelCase($attribute);
            $result[$propertyName] = $this->$attribute;
        }

        return $result;
    }

    private function toCamelCase(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords(preg_replace('/[^A-z0-9_]/', '', $string), '_')));
    }

    private function fromCamelCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    public function validate($skip = []): bool
    {
        $validators = $this->createValidators($skip);

        foreach ($validators as $validator) {
            $validator->validate();
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function addError($attribute, $error = '')
    {
        $this->errors[$attribute][] = $error;
    }

    public function createValidators($skip = [])
    {
        $validators = [];
        foreach ($this->rules() as $rule) {
            if (is_array($rule) && isset($rule[0], $rule[1])) {
                foreach ($skip as $key => $value) {
                    foreach ($value as $v) {
                        if ($rule[1] === $v) {
                            if (is_string($rule[0])) {
                                if ($rule[0] === $key) {
                                    continue 3;
                                }
                            } else {
                                if ($k = array_search($key, (array) $rule[0])) {
                                    unset($rule[0][$k]);
                                }
                            }
                        }
                    }
                }
                
                $validators[] = Validator::createValidator($this, $rule);
            } else {
                throw new InvalidConfigException('Неверный конфиг правил валидации');
            }
        }

        return $validators;
    }
}