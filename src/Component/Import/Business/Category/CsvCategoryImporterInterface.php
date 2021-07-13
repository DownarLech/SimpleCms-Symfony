<?php declare(strict_types=1);

namespace App\Component\Import\Business\Category;

use App\DataTransferObject\CategoryDataProvider;

interface CsvCategoryImporterInterface
{
    public function loadDataAsCsvDto(string $path): array;

}