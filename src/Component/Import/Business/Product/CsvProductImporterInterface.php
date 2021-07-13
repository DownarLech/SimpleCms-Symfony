<?php declare(strict_types=1);


namespace App\Component\Import\Business\Product;


interface CsvProductImporterInterface
{
    public function loadDataAsCsvDto(string $path): array;

}