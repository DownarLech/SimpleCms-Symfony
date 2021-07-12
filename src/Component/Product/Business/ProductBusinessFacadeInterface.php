<?php declare(strict_types=1);


namespace App\Component\Product\Business;


use App\DataTransferObject\CsvProductDataProvider;

interface ProductBusinessFacadeInterface
{

    public function delete(CsvProductDataProvider $product): void;

    public function save(CsvProductDataProvider $csvProductDataProvider): CsvProductDataProvider;

    public function getProductList(): array;

    public function getProductById(int $id): ?CsvProductDataProvider;

    public function getProductByCsvNumber(string $productCsvNumber): ?CsvProductDataProvider;
}