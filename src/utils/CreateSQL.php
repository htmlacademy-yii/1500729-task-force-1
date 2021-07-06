<?php


namespace taskforce\utils;


class CreateSQL
{
    private array $dataHead;
    private array $data;
    private string $table;


    public function __construct(array $dataHead, array $data, string $table)
    {
        $this->dataHead = $dataHead;
        $this->table = $table;
        $this->data = $data;
    }

    private function getRow(): string
    {
        return trim(implode(', ', $this->dataHead));
    }

    public function getQuery(): string
    {
        foreach ($this->getValues() as $string) {
            $value = $string;
            $query[] = 'INSERT INTO ' . $this->getTableName() . '(' . $this->getRow() . ') VALUES (' . $value . ');';
        }
        return implode('', $query);
    }

    public function getValues(): iterable
    {
        foreach ($this->data as $values) {
            yield implode(', ', array_map(function ($string) {
                return '"' . $string . '"';
            }, $values));
        }
    }

    public function getTableName(): string
    {
        $tableName = substr($this->table, 0, strpos($this->table, '.'));
        return $tableName = substr($tableName, 5);
    }
}
