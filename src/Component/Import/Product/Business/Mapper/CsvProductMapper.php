<?php declare(strict_types=1);


namespace App\Component\Import\Product\Business\Mapper;


use App\DataTransferObject\CsvProductDataProvider;

class CsvProductMapper implements CsvProductMapperInterface
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

}