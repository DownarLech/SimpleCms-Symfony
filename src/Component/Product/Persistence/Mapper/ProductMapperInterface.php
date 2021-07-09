<?php declare(strict_types=1);

namespace App\Component\Product\Persistence\Mapper;

use App\DataTransferObject\CsvProductDataProvider;
use App\DataTransferObject\ProductDataProvider;
use App\Entity\Product;

interface ProductMapperInterface
{
    public function mapEntityToCsvProductDto(Product $product, CsvProductDataProvider $csvProductDto): CsvProductDataProvider;

    public function mapCsvProductDtoToEntity(CsvProductDataProvider $csvProductDto, Product $product): Product;

    public function mapEntityToDataProvider(Product $product, ProductDataProvider $productDataProvider): ProductDataProvider;

    public function mapDataProviderToEntity(ProductDataProvider $productDataProvider, Product $product): Product;

}