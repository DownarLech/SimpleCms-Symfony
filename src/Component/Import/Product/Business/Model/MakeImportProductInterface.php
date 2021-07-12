<?php declare(strict_types=1);


namespace App\Component\Import\Product\Business\Model;


interface MakeImportProductInterface
{
    public function saveProductsFromCsvFile(string $path): array;

}