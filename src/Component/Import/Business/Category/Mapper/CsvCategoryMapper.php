<?php declare(strict_types=1);


namespace App\Component\Import\Business\Category\Mapper;


use App\DataTransferObject\CategoryDataProvider;

class CsvCategoryMapper implements CsvCategoryMapperInterface
{
    public function mapIteratorToCategoryDataProvider(array $records, CategoryDataProvider $categoryDataProvider): CategoryDataProvider
    {
        $categoryDataProvider->setName($records['category_key']);

        return $categoryDataProvider;
    }

}