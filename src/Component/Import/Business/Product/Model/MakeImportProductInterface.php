<?php declare(strict_types=1);


namespace App\Component\Import\Business\Product\Model;


interface MakeImportProductInterface
{
    public function saveProductsFromCsvFile(string $path): array;

}