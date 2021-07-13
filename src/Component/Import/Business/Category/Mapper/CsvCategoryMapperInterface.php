<?php declare(strict_types=1);

namespace App\Component\Import\Business\Category\Mapper;

use App\DataTransferObject\CategoryDataProvider;

interface CsvCategoryMapperInterface
{
    public function mapIteratorToCategoryDataProvider(array $records, CategoryDataProvider $categoryDataProvider): CategoryDataProvider;
}