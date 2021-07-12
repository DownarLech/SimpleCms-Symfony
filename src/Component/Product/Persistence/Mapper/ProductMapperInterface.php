<?php declare(strict_types=1);

namespace App\Component\Product\Persistence\Mapper;

use App\DataTransferObject\CsvProductDataProvider;
use App\Entity\Product;

interface ProductMapperInterface
{
    public function mapEntityToCsvProductDto(Product $product, CsvProductDataProvider $csvProductDto): CsvProductDataProvider;

    public function mapCsvProductDtoToEntity(CsvProductDataProvider $csvProductDto, Product $product): Product;


}