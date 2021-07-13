<?php


namespace taskforce\utils;


class CreateSQL
{
    private array $dataHead;
    public array $data;
    private string $table;


    public function __construct(array $dataHead, string $table)
    {
        $this->dataHead = $dataHead;
        $this->table = $table;
    }

    private function getRow(): string
    {
        return trim(implode(', ', $this->dataHead));
    }

    public function getQuery($data): string
    {
        $value = $this->getValues($data);
        return $query = 'INSERT INTO ' . $this->getTableName() . '(' . $this->getRow() . ') VALUES (' . $value . ');';

    }

    public function getValues($data): string
    {
            return implode(', ', array_map(function ($string) {
                return '"' . $string . '"';
            }, $data));
    }

    public function getTableName(): string
    {
        $tableName = substr($this->table, 0, strpos($this->table, '.'));
        return $tableName = substr($tableName, 5);
    }
}
