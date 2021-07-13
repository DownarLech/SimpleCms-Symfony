<?php declare(strict_types=1);


namespace App\Component\Import\Business\Product\Mapper;


use App\DataTransferObject\CsvProductDataProvider;

interface CsvProductMapperInterface
{
    public function mapIteratorToCsvProductDataProvider(array $records, CsvProductDataProvider $csvProductDto): CsvProductDataProvider;

}