<?php declare(strict_types=1);


namespace App\Component\Product\Business\Csv\Mapper;

use App\DataTransferObject\CategoryDataProvider;
use App\DataTransferObject\CsvProductDataProvider;


class CsvMapper
{

    public function mapIteratorToCsvProductDataProvider(array $records, CsvProductDataProvider $csvProductDto): CsvProductDataProvider
    {
        //$csvProductDto->setId((int)$records['abstract_sku']);
        $csvProductDto->setName($records['name.en_US']);
        $csvProductDto->setDescription($records['description.en_US']);
        $csvProductDto->setProductCsvNumber($records['abstract_sku']);
        $csvProductDto->setCategoryName($records['category_key']);

        return $csvProductDto;
    }

    public function mapIteratorToCategoryDataProvider(array $records, CategoryDataProvider $categoryDataProvider): CategoryDataProvider
    {
        $categoryDataProvider->setName($records['category_key']);

        return $categoryDataProvider;
    }
}