<?php declare(strict_types=1);

namespace App\Component\Import\Category\Business\Mapper;

use App\DataTransferObject\CategoryDataProvider;

interface CsvCategoryMapperInterface
{
    public function mapIteratorToCategoryDataProvider(array $records, CategoryDataProvider $categoryDataProvider): CategoryDataProvider;
}