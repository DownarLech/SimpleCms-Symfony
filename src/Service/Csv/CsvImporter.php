<?php declare(strict_types=1);

namespace App\Service\Csv;

use League\Csv\Reader;

class CsvImporter
{
    private array $header;

    public function __construct()
    {
    }

    /**
     * @return \Iterator data provider
     * @throws \League\Csv\Exception
     */
    public function loadCsvData(string $path): \Iterator
    {
        $readerCsv = Reader::createFromPath($path);
        $readerCsv->setHeaderOffset(0);

        $this->header = $readerCsv->getHeader();
        return $readerCsv->getRecords();
    }

    public function getHeader(): array
    {
        return $this->header;
    }

}