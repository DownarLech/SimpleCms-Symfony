<?php declare(strict_types=1);

namespace App\Component\Import\Business;


interface ImportBusinessFacadeInterface
{
    public function saveProductsFromCsvFile(string $path): array;

    public function saveCategoriesFromCsvFile(string $path): array;

}