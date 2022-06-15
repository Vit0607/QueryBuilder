<?php

class QueryBuilder
{
    protected $pdo;

    protected function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //    Получение всех записей из БД:
    public function getAll($table) //    $table - название таблицы в БД
    {
        $sql = "SELECT * FROM {$table}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //    Получение одной записи из БД:
    public function getOne($table, $id) //    $table - название таблицы в БД, $id - id записи
    {
        $sql = "SELECT * FROM {$table} WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //    Добавление записи в БД:
    public function create($table, $data) //    $table - название таблицы в БД, $data - массив полей и их значений
    {
        $keys = implode(',', array_keys($data));
        $tags = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$keys}) VALUES ({$tags})";

        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);
    }

    //    Обновление записи в БД:
    public function update($table, $data, $id) //    $table - название таблицы в БД, $data - массив полей и их значений, $id - id записи
    {
        $keys = array_keys($data);
        $string = '';

        foreach ($keys as $key) {
            $string .= $key . '=:' . $key . ',';
        }

        $keys = rtrim($string, ',');

        $data['id'] = $id;

        $sql = "UPDATE {$table} SET {$keys} WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);
    }

    //    Удаление одной записи из БД:
    public function delete($table, $id) //    $table - название таблицы в БД, $id - id записи
    {
        $sql = "DELETE FROM {$table} WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['id' => $id]);
    }
}
