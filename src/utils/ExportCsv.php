<?php


namespace taskforce\utils;


use RuntimeException;
use taskforce\exceptions\FileException;
use SplFileObject;

class ExportCsv
{
    private string $filename;
    private SplFileObject $fileobject;
    private CreateSQL $createSQL;

    private array $res = [];

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function export(): void
    {
        if (!file_exists($this->filename)) {
            throw new FileException("Файл не существует");
        }
        $this->fileobject = new SplFileObject($this->filename);
    }


    public function getHeaderData(): ?array
    {
        $this->fileobject->rewind();
        return $this->fileobject->fgetcsv();
    }

    public function getNextLine(): array
    {

        while (!$this->fileobject->eof()) {
            $this->res[] = $this->fileobject->fgetcsv();
        }
        return $this->res;
    }

    public function getQuery(): string
    {
        $this->createSQL = new CreateSQL($this->getHeaderData(), $this->getNextLine(), $this->filename);
        return $this->createSQL->getQuery();
    }

    public function createFileSQL(): void
    {
        try {
            $content = $this->getQuery();
            $fp = fopen('data/' . $this->createSQL->getTableName() . '.sql', "w");
            fwrite($fp, $content);
            fclose($fp);
        } catch (RuntimeException $exception) {
            throw new FileException("Не удалось создать дамп из файла " . $this->filename);
        }
    }
}
