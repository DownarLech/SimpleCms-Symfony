<?php declare(strict_types=1);

namespace App\Component\Import\Category\Business\Model;


use App\DataTransferObject\CsvProductDataProvider;

interface MakeImportCategoryInterface
{
    public function saveCategoriesFromCsvFile(string $path): array;

}