<?php declare(strict_types=1);


namespace App\Component\Import\Product\Business;


interface CsvProductImporterInterface
{
    public function loadDataAsCsvDto(string $path): array;

}