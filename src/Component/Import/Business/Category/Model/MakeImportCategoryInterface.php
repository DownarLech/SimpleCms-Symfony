<?php declare(strict_types=1);

namespace App\Component\Import\Business\Category\Model;


use App\DataTransferObject\CsvProductDataProvider;

interface MakeImportCategoryInterface
{
    public function saveCategoriesFromCsvFile(string $path): array;

}