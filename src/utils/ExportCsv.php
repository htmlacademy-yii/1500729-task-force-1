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

    private array $res;

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

    public function getNextLine(): ?iterable
    {
        while (!$this->fileobject->eof()) {
            yield $this->fileobject->fgetcsv();
        }
    }

    public function createFileSQL(): void
    {
        $this->createSQL = new CreateSQL($this->getHeaderData(), $this->filename);

        try {
            $fp = fopen('data/' . $this->createSQL->getTableName() . '.sql', "w");
            foreach ($this->getNextLine() as $line) {
                $content = $this->createSQL->getQuery($line);
                fwrite($fp, $content);
            }
            fclose($fp);
        } catch (RuntimeException $exception) {
            throw new FileException("Не удалось создать дамп из файла " . $this->filename);
        }
    }
}
