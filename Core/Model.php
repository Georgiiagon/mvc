<?php

namespace Core;

use PDO;
use Config\Database;

abstract class Model
{
	static $db = null;

	protected $table = null;

    protected $attributes = [];

	function __construct($attributes = [])
	{
		if (self::$db === null) {
			$bdd = 'mysql:host=' . Database::HOST . ';dbname=' . Database::NAME . ';charset=utf8';
			self::$db = new PDO($bdd, Database::USER, Database::PASSWORD);
		}

		$this->attributes = $attributes;
	}

    function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    function __get($name)
    {
        return $this->attributes[$name];
    }

    public function save()
    {
        $key = @$this->{$this->key};
        return $key ? $this->update() : $this->store();
    }

    private function store()
    {
        $values = $columns = array_keys($this->attributes);
        $columns = implode(',', $columns);
        $values = ':' . implode(',:', $values);

        $sql = self::$db->prepare("INSERT INTO `$this->table` ($columns) VALUES ($values)");

        foreach ($this->attributes as $key => $value)
            $sql->bindValue(":$key", $value);

        $result = $sql->execute();

        $this->{$this->key} = self::$db->lastInsertId();

        return $result;
    }

    public function update()
    {
        $fields = [];

        foreach ($this->attributes as $key => $value) {
            if($key == $this->key)
                continue;

            $fields[] = "$key = :$key";
        }

        $fields = implode(',', $fields);

        $sql = self::$db->prepare("UPDATE `$this->table` SET $fields WHERE id = :id");

        foreach ($this->attributes as $key => $value) {
            if($key == $this->key)
                continue;

            $sql->bindValue(":$key", $value);
        }

        $sql->bindValue(":id", $this->id);

        return $sql->execute();
    }

    public static function count()
    {
        $table = (new static())->table;

        return self::$db->query("SELECT COUNT(*) as count FROM `$table`")
            ->fetchColumn();
    }

    public static function find($id)
    {
        $table = (new static())->table;

        $sql = self::$db->prepare("SELECT * FROM `$table` WHERE id = :id");

        $sql->bindValue(':id', (int) $id, PDO::PARAM_INT);

        $sql->execute();

        return new static($sql->fetch(PDO::FETCH_ASSOC));
    }

    public static function paginate($page, $limit, $order, $orderDir)
    {
        $order = static::$db->quote(str_replace("'", '', $order));
        $orderDir = static::$db->quote(str_replace("'", '', $orderDir));
		$order = str_replace("'", '', $order);
		$orderDir = str_replace("'", '', $orderDir);

        $table = (new static())->table;

        $start = ($page - 1) * $limit;
        $sql = self::$db->prepare("SELECT * FROM `$table` ORDER BY $order $orderDir LIMIT :start, :limit");

        $sql->bindValue(':start', (int) $start, PDO::PARAM_INT);
        $sql->bindValue(':limit', (int) $limit, PDO::PARAM_INT);

        $sql->execute();

        $models = [];
        foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $item)
            $models[] = new static($item);

        return $models;
    }
}
