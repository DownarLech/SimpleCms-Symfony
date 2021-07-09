<?php declare(strict_types=1);

namespace App\Component\Product\Persistence;

use App\DataTransferObject\CsvProductDataProvider;
use App\DataTransferObject\ProductDataProvider;

interface ProductReaderInterface
{
    public function getProductList(): array;

    public function getProductById(int $id): ?CsvProductDataProvider;

    public function getProductByCsvNumber(string $productCsvNumber): ?CsvProductDataProvider;

}