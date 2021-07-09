<?php declare(strict_types=1);


namespace App\Component\Product\Business;


use App\DataTransferObject\CategoryDataProvider;
use App\DataTransferObject\CsvProductDataProvider;
use App\DataTransferObject\ProductDataProvider;

interface ProductBusinessFacadeInterface
{

    public function delete(CsvProductDataProvider $product): void;

    public function save(CsvProductDataProvider $csvProductDataProvider): CsvProductDataProvider;

    public function getProductList(): array;

    public function getProductById(int $id): ?CsvProductDataProvider;

}